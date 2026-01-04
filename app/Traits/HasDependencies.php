<?php

namespace App\Traits;

use App\Exceptions\DependencyExistsException;

trait HasDependencies
{
    public function validateNoDependencies(): void
    {
        $models = method_exists($this, 'dependentModels') ? $this->dependentModels() : []   ;
        foreach (  $models as $relation) {
            if ($this->{$relation}()->exists()) {
              $message = method_exists($this, 'dependencyExceptionMessage') 
                ? $this->dependencyExceptionMessage()
                    : "Cannot delete record: dependencies exist in {$relation}.";
                throw new DependencyExistsException( $message);
            }
        }
    }
}