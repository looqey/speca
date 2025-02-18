<?php

namespace Looqey\Speca\Core\Context;

class ObjectContext
{
    /**
     * Массив путей для включения (dot‑notation). Например: ['nested1.nested2.lazyField'].
     * Если пуст – по умолчанию включаются все поля (если не исключены явно).
     *
     * @var string[]
     */
    public array $includes = [];

    /**
     * Массив путей для исключения.
     *
     * @var string[]
     */
    public array $excludes = [];

    /**
     * Проверяет, включено ли поле с указанным путем.
     *
     * @param string $propertyPath
     * @return bool
     */
    public function isIncluded(string $propertyPath): bool
    {
        // Сначала проверяем исключения: если путь совпадает или начинается с исключения, поле не включается
        foreach ($this->excludes as $exclude) {
            if ($exclude === $propertyPath || strpos($propertyPath, $exclude . '.') === 0) {
                return false;
            }
        }
        // Если список includes пуст – всё включено
        if (empty($this->includes)) {
            return false;
        }
        // Иначе проверяем, соответствует ли путь хотя бы одному из include-путей
        foreach ($this->includes as $include) {
            if ($include === $propertyPath || str_starts_with($propertyPath, $include . '.')) {
                return true;
            }
        }
        return false;
    }

    public function isExcluded(string $propertyPath): bool
    {
        foreach ($this->excludes as $exclude) {
            if ($exclude === $propertyPath || strpos($propertyPath, $exclude . '.') === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Создает новый ObjectContext для вложенного объекта.
     * Из текущего контекста выбираются только те пути, которые начинаются с заданного префикса.
     * Префикс (и точка) отбрасываются.
     *
     * @param string $prefix
     * @return self
     */
    public function filterForNested(string $prefix): self
    {
        $nested = new self();
        foreach ($this->includes as $include) {
            if (str_starts_with($include, $prefix . '.')) {
                $nested->includes[] = substr($include, strlen($prefix) + 1);
            }
        }
        foreach ($this->excludes as $exclude) {
            if (str_starts_with($exclude, $prefix . '.')) {
                $nested->excludes[] = substr($exclude, strlen($prefix) + 1);
            }
        }
        return $nested;
    }
}
