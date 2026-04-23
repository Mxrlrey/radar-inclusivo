<?php

namespace App\Services;

use App\Models\Traits\Reportable;
use BackedEnum;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;
use Throwable;

class ReportService
{
    /**
     * RF: lista as entidades habilitadas para o builder de relatórios.
     * Uso: popula o seletor inicial com os models reportáveis do sistema.
     */
    public function availableEntities(): array
    {
        $modelsPath = app_path('Models');
        $files = collect(File::allFiles($modelsPath));

        return $files
            ->map(function ($file) use ($modelsPath) {
                $relative = str_replace(
                    [$modelsPath . DIRECTORY_SEPARATOR, '.php'],
                    '',
                    $file->getPathname()
                );

                $class = 'App\\Models\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $relative);

                if (!class_exists($class) || !$this->isReportable($class)) {
                    return null;
                }

                return [
                    'class' => $class,
                    'label' => $class::getReportLabel(),
                ];
            })
            ->filter()
            ->sortBy('label')
            ->values()
            ->all();
    }

    /**
     * RF: monta os metadados de uma entidade com colunas e relações disponíveis.
     * Uso: carrega o builder após o usuário escolher o tipo base do relatório.
     */
    public function meta(string $modelClass): array
    {
        $this->assertValidReportableModel($modelClass);

        $model = new $modelClass();
        $table = $model->getTable();
        $columns = $modelClass::getTranslatedColumns()->toArray();

        $declaredColumns = method_exists($modelClass, 'getReportColumns')
            ? $modelClass::getReportColumns()
            : null;

        $hasDeclaredColumns = is_array($declaredColumns) && !empty($declaredColumns);

        $allowedEmbedded = (!$hasDeclaredColumns && is_callable([$modelClass, 'getEmbeddedRelations']))
            ? (array) $modelClass::getEmbeddedRelations()
            : [];
        $configuredRelations = $this->reportRelationsFor($modelClass);

        $relations = [];
        $reflector = new ReflectionClass($modelClass);

        foreach ($reflector->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->class !== $reflector->getName() || $method->getNumberOfParameters() > 0) {
                continue;
            }

            try {
                $return = $method->invoke($model);
            } catch (Throwable) {
                continue;
            }

            if (!$return instanceof Relation) {
                continue;
            }

            $relationName = $method->getName();

            if ($this->isIgnoredRelation($relationName) || array_key_exists($relationName, $configuredRelations)) {
                continue;
            }

            $relation = $model->$relationName();
            $related = $relation->getRelated();
            $relatedClass = get_class($related);

            if (!$this->isReportable($relatedClass)) {
                continue;
            }

            $relCols = $this->translatedColumnsForRelatedModel($relatedClass, $related->getTable());

            $relData = [
                'name' => $relationName,
                'type' => class_basename(get_class($relation)),
                'related_class' => $relatedClass,
                'label' => is_callable([$relatedClass, 'getReportLabel'])
                    ? $relatedClass::getReportLabel()
                    : class_basename($relatedClass),
                'table' => $related->getTable(),
                'columns' => $relCols,
            ];

            if ($relation instanceof BelongsToMany) {
                $pivotColumns = $this->resolvePivotColumns($relation);

                if (!empty($pivotColumns)) {
                    $relData['pivot'] = [
                        'table' => $relation->getTable(),
                        'columns' => $pivotColumns,
                    ];
                }
            }

            $isSingular = $relation instanceof BelongsTo
                || $relation instanceof HasOne
                || $relation instanceof MorphOne;

            $shouldEmbed = !$hasDeclaredColumns
                && $isSingular
                && in_array($relationName, $allowedEmbedded, true);

            if ($shouldEmbed) {
                foreach ($relCols as $colKey => $colLabel) {
                    $composedKey = "{$relationName}.{$colKey}";

                    if (!array_key_exists($composedKey, $columns)) {
                        $columns[$composedKey] = $colLabel;
                    }
                }
            }

            $relations[] = $relData;
        }

        foreach ($configuredRelations as $relationName => $relationMeta) {
            $relatedClass = $relationMeta['target'];
            $related = new $relatedClass();
            $relCols = $this->translatedColumnsForRelatedModel($relatedClass, $related->getTable());

            $relations[] = [
                'name' => $relationName,
                'type' => 'MorphTo',
                'related_class' => $relatedClass,
                'label' => $relationMeta['label'] ?? $relatedClass::getReportLabel(),
                'table' => $related->getTable(),
                'columns' => $relCols,
            ];
        }

        return [
            'class' => $modelClass,
            'label' => $modelClass::getReportLabel(),
            'table' => $table,
            'columns' => $columns,
            'relations' => array_values($relations),
        ];
    }

    /**
     * RF: executa um relatório dinâmico com colunas, filtros e relações selecionadas.
     * Uso: gera a prévia tabular exibida na tela de relatórios.
     */
    public function run(array $payload): array
    {
        $modelClass = $payload['model'] ?? null;
        $selected = $payload['columns'] ?? [];
        $filters = $payload['filters'] ?? [];
        $limit = (int) ($payload['limit'] ?? 200);

        $this->assertValidReportableModel($modelClass);

        $query = $modelClass::query();
        $relationsToLoad = $this->relationsToLoadFor($modelClass, $selected, $filters);

        if ($relationsToLoad) {
            $query->with($relationsToLoad);
        }

        foreach ($filters as $filter) {
            $column = $filter['column'] ?? null;
            $operator = strtolower($filter['operator'] ?? '=');
            $value = $filter['value'] ?? null;

            if (!$column || $value === null || $value === '') {
                continue;
            }

            if (str_contains($column, '.')) {
                [$relation, $relationColumn] = explode('.', $column, 2);
                $polymorphicMeta = $this->reportRelationMeta($modelClass, $relation);

                if ($polymorphicMeta) {
                    $targetClass = $polymorphicMeta['class'];
                    $morphType = $this->morphTypeFor($targetClass);

                    $query
                        ->where($polymorphicMeta['type_column'], $morphType)
                        ->whereHasMorph(
                            $polymorphicMeta['relation'],
                            [$targetClass],
                            function ($q) use ($relationColumn, $operator, $value) {
                                strtolower($operator) === 'like'
                                    ? $q->where($relationColumn, 'like', "%{$value}%")
                                    : $q->where($relationColumn, $operator, $value);
                            }
                        );

                    continue;
                }

                $query->whereHas($relation, function ($q) use ($relationColumn, $operator, $value) {
                    strtolower($operator) === 'like'
                        ? $q->where($relationColumn, 'like', "%{$value}%")
                        : $q->where($relationColumn, $operator, $value);
                });

                continue;
            }

            strtolower($operator) === 'like'
                ? $query->where($column, 'like', "%{$value}%")
                : $query->where($column, $operator, $value);
        }

        $rows = $query->limit($limit)->get();
        $result = [];

        foreach ($rows as $row) {
            $out = [];

            foreach ($selected as $columnKey) {
                $alias = str_replace('.', '__', $columnKey);
                $value = data_get($row, $columnKey);

                if (str_contains($columnKey, '.')) {
                    [$relationName, $relationColumn] = explode('.', $columnKey, 2);
                    $polymorphicMeta = $this->reportRelationMeta($modelClass, $relationName);

                    if ($polymorphicMeta) {
                        $targetClass = $polymorphicMeta['class'];
                        $morphType = $this->morphTypeFor($targetClass);

                        $value = $row->{$polymorphicMeta['type_column']} === $morphType
                            ? data_get($row->{$polymorphicMeta['relation']}, $relationColumn)
                            : null;
                    }
                }

                if ($value === null && str_contains($columnKey, '.')) {
                    [$relationName, $relationColumn] = explode('.', $columnKey, 2);
                    $relationValue = $row->$relationName ?? null;

                    if ($relationValue instanceof Collection) {
                        $value = $relationValue
                            ->map(fn ($item) => data_get($item, $relationColumn))
                            ->filter()
                            ->unique()
                            ->values()
                            ->implode(', ');
                    }
                }

                $out[$alias] = $this->normalizeValue($value);
            }

            $result[] = $out;
        }

        return [
            'rows' => $result,
            'total' => $rows->count(),
        ];
    }

    /**
     * RF: reaproveita a execução do relatório com limite ampliado para exportação.
     * Uso: entrega os dados normalizados para saídas externas como PDF.
     */
    public function exportData(array $payload, int $limit = 1000): array
    {
        $payload['limit'] = $limit;

        return $this->run($payload);
    }

    /**
     * RF: valida se um model participa do módulo de relatórios via trait reportável.
     * Uso: protege metadata e consultas contra entidades fora do escopo do builder.
     */
    private function isReportable(string $class): bool
    {
        return in_array(Reportable::class, class_uses_recursive($class), true);
    }

    /**
     * RF: garante que o model informado exista e seja aceito pelo motor de relatórios.
     * Uso: valida a entrada antes de montar metadata ou consultar dados.
     */
    private function assertValidReportableModel(?string $modelClass): void
    {
        if (!$modelClass || !class_exists($modelClass)) {
            throw new InvalidArgumentException('Modelo inválido');
        }

        if (!$this->isReportable($modelClass)) {
            throw new InvalidArgumentException('Modelo não reportável');
        }
    }

    /**
     * RF: resolve relações especiais declaradas pelo próprio model para o builder.
     * Uso: permite que cada entidade defina seu contrato de relações de relatório.
     */
    private function reportRelationsFor(string $modelClass): array
    {
        if (!is_callable([$modelClass, 'getReportRelations'])) {
            return [];
        }

        $relations = $modelClass::getReportRelations();

        return is_array($relations) ? $relations : [];
    }

    /**
     * RF: resolve os metadados normalizados de uma relação especial declarada no model.
     * Uso: orienta filtros e leitura de colunas para relações polimórficas do builder.
     */
    private function reportRelationMeta(string $modelClass, string $relationName): ?array
    {
        $relations = $this->reportRelationsFor($modelClass);
        $relation = $relations[$relationName] ?? null;

        if (!$relation) {
            return null;
        }

        return [
            'relation' => $relation['relation'],
            'type_column' => $relation['type_column'],
            'class' => $relation['target'],
            'label' => $relation['label'] ?? null,
        ];
    }

    /**
     * RF: oculta relações técnicas que não devem aparecer como opção direta no builder.
     * Uso: evita exposição de nomes genéricos usados apenas pela infraestrutura polimórfica.
     */
    private function isIgnoredRelation(string $relationName): bool
    {
        return in_array($relationName, ['inspectable', 'loanable', 'waitlistable'], true);
    }

    /**
     * RF: resolve labels amigáveis das colunas de um model relacionado.
     * Uso: apresenta campos compreensíveis no seletor de dados relacionados.
     */
    private function translatedColumnsForRelatedModel(string $relatedClass, string $table): array
    {
        if (is_callable([$relatedClass, 'getTranslatedColumns'])) {
            try {
                $columns = $relatedClass::getTranslatedColumns()->toArray();
                if (!empty($columns)) {
                    return $columns;
                }
            } catch (Throwable) {
            }
        }

        $blacklist = method_exists($relatedClass, 'getBlacklist')
            ? $relatedClass::getBlacklist()
            : ['password', 'remember_token', 'deleted_at'];

        return collect(Schema::getColumnListing($table))
            ->reject(fn ($column) => in_array($column, $blacklist, true))
            ->mapWithKeys(function ($column) use ($table) {
                $translationKey = "database.columns.{$table}.{$column}";
                $translation = __($translationKey);

                $label = $translation === $translationKey
                    ? Str::title(str_replace('_', ' ', $column))
                    : $translation;

                return [$column => $label];
            })
            ->toArray();
    }

    /**
     * RF: identifica colunas úteis da tabela pivô em relações muitos-para-muitos.
     * Uso: expõe campos do vínculo quando a relação relacionada possui pivot.
     */
    private function resolvePivotColumns(BelongsToMany $relation): array
    {
        $pivotTable = $relation->getTable();
        $pivotColumns = [];

        if (method_exists($relation, 'getPivotColumns')) {
            try {
                $pivotColumns = $relation->getPivotColumns();
            } catch (Throwable) {
                $pivotColumns = [];
            }
        }

        if (empty($pivotColumns)) {
            $allPivotColumns = Schema::getColumnListing($pivotTable);
            $foreign1 = method_exists($relation, 'getForeignPivotKeyName')
                ? $relation->getForeignPivotKeyName()
                : null;
            $foreign2 = method_exists($relation, 'getRelatedPivotKeyName')
                ? $relation->getRelatedPivotKeyName()
                : null;

            $exclude = array_filter([$foreign1, $foreign2, 'id', 'created_at', 'updated_at']);

            $pivotColumns = array_values(
                array_filter($allPivotColumns, fn ($column) => !in_array($column, $exclude, true))
            );
        }

        $pivotColumns = array_values(
            array_filter($pivotColumns, fn ($column) => !in_array($column, ['created_at', 'updated_at'], true))
        );

        $labels = [];

        foreach ($pivotColumns as $column) {
            $translationKey = "database.columns.{$pivotTable}.{$column}";
            $translation = __($translationKey);

            $labels[$column] = $translation === $translationKey
                ? Str::title(str_replace('_', ' ', $column))
                : $translation;
        }

        return $labels;
    }

    /**
     * RF: identifica quais relações precisam ser carregadas para executar o relatório.
     * Uso: prepara o acesso às colunas relacionais na prévia sem consultas redundantes.
     */
    private function relationsToLoadFor(string $modelClass, array $selected, array $filters): array
    {
        $relationsToLoad = [];

        foreach (array_merge($selected, array_column($filters, 'column')) as $column) {
            if (!str_contains($column ?? '', '.')) {
                continue;
            }

            $relationName = explode('.', $column)[0];
            $polymorphicMeta = $this->reportRelationMeta($modelClass, $relationName);

            if ($polymorphicMeta) {
                $relationsToLoad[] = $polymorphicMeta['relation'];
                continue;
            }

            $relationsToLoad[] = $relationName;
        }

        return array_values(array_unique(array_filter($relationsToLoad)));
    }

    /**
     * RF: resolve o tipo morfológico persistido para um model polimórfico.
     * Uso: compatibiliza filtros com o morph map global definido pela aplicação.
     */
    private function morphTypeFor(string $modelClass): string
    {
        return (new $modelClass())->getMorphClass();
    }

    /**
     * RF: normaliza valores brutos para apresentação consistente no relatório.
     * Uso: converte enums, datas, coleções e booleanos antes da resposta do builder.
     */
    private function normalizeValue(mixed $value): mixed
    {
        if ($value instanceof Collection) {
            $value = $value->filter()->unique()->values()->implode(', ');
        }

        if (is_bool($value)) {
            return $value ? 'Sim' : 'Não';
        }

        if ($value instanceof BackedEnum) {
            return method_exists($value, 'label') ? $value->label() : $value->value;
        }

        if ($value instanceof CarbonInterface) {
            $hasTime = $value->hour > 0 || $value->minute > 0 || $value->second > 0;

            return $value->format($hasTime ? 'd/m/Y H:i' : 'd/m/Y');
        }

        if (is_object($value) && !method_exists($value, '__toString')) {
            return json_encode($value);
        }

        return $value;
    }
}
