<?php declare(strict_types=1);

namespace Rector\Doctrine\AbstarctRector;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\Doctrine\PhpDocParser\DoctrineDocBlockResolver;

trait DoctrineTrait
{
    /**
     * @var DoctrineDocBlockResolver
     */
    private $doctrineDocBlockResolver;

    /**
     * @required
     */
    public function autowireDoctrineTrait(DoctrineDocBlockResolver $doctrineDocBlockResolver): void
    {
        $this->doctrineDocBlockResolver = $doctrineDocBlockResolver;
    }

    protected function isDoctrineEntityClass(Class_ $class): bool
    {
        return $this->doctrineDocBlockResolver->isDoctrineEntityClass($class);
    }

    protected function isDoctrineEntityClassWithIdProperty(Class_ $class): bool
    {
        if (! $this->doctrineDocBlockResolver->isDoctrineEntityClass($class)) {
            return false;
        }

        foreach ($class->stmts as $classStmt) {
            if (! $classStmt instanceof Property) {
                continue;
            }

            if ($this->doctrineDocBlockResolver->hasPropertyDoctrineIdTag($classStmt)) {
                return true;
            }
        }

        return false;
    }

    protected function getTargetEntity(Property $property): ?string
    {
        return $this->doctrineDocBlockResolver->getTargetEntity($property);
    }
}
