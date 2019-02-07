<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.06.17
 * Time: 21:05
 */

namespace MoncaretWS\ContentWidgetsBundle\EventListener;


use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;

class DoctrineEventSubscriber implements EventSubscriber
{

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metaData */
        $metaData = $eventArgs->getClassMetadata();

        if ($metaData->discriminatorValue === null && count($metaData->parentClasses) > 0) {
            $metaData->discriminatorValue = $metaData->table['name'];

            foreach ($metaData->parentClasses as $parentClass) {
                /** @var ClassMetadata $parentMetadata */
                $parentMetadata = $eventArgs->getObjectManager()->getClassMetadata($parentClass);
                if (!in_array($metaData->name, $parentMetadata->subClasses)) $parentMetadata->subClasses[] = $metaData->name;
                if (!array_key_exists($metaData->discriminatorValue, $parentMetadata->discriminatorMap)) {
                    $parentMetadata->addDiscriminatorMapClass($metaData->discriminatorValue, $metaData->name);
                }
            }
        }
    }

    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata
        ];
    }

}