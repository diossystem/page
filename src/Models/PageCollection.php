<?php

namespace Dios\System\Page\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Collection;

/**
 *
 */
class PageCollection extends Collection
{
    public function loadRecursively(string $relation, $maxDepth = null)
    {
        if ($this->count() === 0 || (isset($maxDepth) && $maxDepth < 0)) {
            return;
        }

        $firstRelation = $this->first()->$relation();

        if ($firstRelation instanceof HasMany) {
            $this->load($relation);

            if ($this->pluck($relation)->count()) {
                if (isset($maxDepth)) {
                    $maxDepth--;
                }

                foreach ($this->pluck($relation) as $children) {
                    $children->loadRecursively($relation, $maxDepth);
                }
                // echo $this->count()." loaded ".implode(' ', $this->getQueueableIds())." \n";
                // if (isset($maxDepth)) {
                //     echo "Dep: ".$maxDepth."\n";
                // }
            }
        } elseif ($firstRelation instanceof HasOne) {
            $this->load($relation);

            if ($this->pluck($relation)->count()) {
                if (isset($maxDepth)) {
                    $maxDepth--;
                }

                $parents = new self($this->pluck($relation)->filter(function ($parent) {
                    return isset($parent);
                }));

                $parents->loadRecursively($relation, $maxDepth);
            }
        }
    }

    public function countRecursivelyLoadedInstances(string $relation): int
    {
        $amount = $this->count();

        if ($amount) {
            $firstRelation = $this->first()->$relation();

            if ($firstRelation instanceof HasMany && $this->pluck($relation)->count()) {
                foreach ($this->pluck($relation) as $children) {
                    $amount += $children->countRecursivelyLoadedInstances($relation);
                }
            } elseif ($firstRelation instanceof HasOne) {
                $parents = new self($this->pluck($relation)->filter(function ($parent) {
                    return isset($parent);
                }));

                $amount += $parents->countRecursivelyLoadedInstances($relation);
            }
        }

        return $amount;
    }
}
