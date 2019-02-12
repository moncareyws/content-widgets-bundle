<?php

/*
 * Created by Samuel Moncarey
 * 16/06/2017
 */

namespace MoncaretWS\ContentWidgetsBundle\Manager;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use MoncaretWS\ContentWidgetsBundle\Entity\Container\MasterContainer;
use MoncaretWS\ContentWidgetsBundle\Entity\Container\WidgetContainer;
use MoncaretWS\ContentWidgetsBundle\Entity\Container\ContainerVersion;
use MoncaretWS\ContentWidgetsBundle\Repository\ContainerVersionRepository;
use MoncaretWS\ContentWidgetsBundle\Repository\WidgetContainerRepository;
use MoncaretWS\ContentWidgetsBundle\Factory\WidgetContainerFactory;


class WidgetContainerManager {

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var WidgetContainerRepository
     */
    private $repository;

    /**
     * @var ContainerVersionRepository
     */
    private $versionsRepository;

    /**
     * @var ClassMetadata
     */
    private $classMetadata;

    /**
     * @var WidgetContainerFactory
     */
    private $factory;

    public function __construct(Registry $registry, WidgetContainerFactory $containerFactory) {

        $this->em = $registry->getManager();
        $this->repository = $this->em->getRepository(WidgetContainer::class);
        $this->versionsRepository = $this->em->getRepository(ContainerVersion::class);
        $this->classMetadata = $this->em->getClassMetadata(WidgetContainer::class);

        $this->factory = $containerFactory;
    }

    /**
     * @param string $name
     * @param bool $createIfMissing
     *
     * @return WidgetContainer
     */
    public function getContainerByName($name, $createIfMissing = false) {
        $container = $this->repository->findOneByName($name);
        if ($container === null && $createIfMissing) {
            $container = $this->factory->createStandaloneContainer($name);
        }
        return $container;
    }

    public function getContainerType(WidgetContainer $container) {
        /** @var ClassMetadata $containerMetadata */
        $containerMetadata = $this->em->getClassMetadata(get_class($container));
        return $containerMetadata->discriminatorValue;
    }

    /**
     * @param MasterContainer $container
     *
     * @return bool|MasterContainer
     */
    public function getSavedContainer(MasterContainer $container) {
        /** @var ContainerVersion $latestContainerVersion */
        $latestContainerVersion = $this->versionsRepository->getLatestContainerVersion($container);
        if ($latestContainerVersion === null) return false;
        $savedContainer = new MasterContainer();
        $savedContainer->unserialize(stream_get_contents($latestContainerVersion->getVersionData()));
        return $savedContainer;
    }

    /**
     * @param MasterContainer $container
     *
     * @return array
     */
    public function getContainerVersions(MasterContainer $container) {
        /** @var ContainerVersion[] $containerVersions */
        $containerVersions = $this->versionsRepository->getContainerVersions($container);
        if ($containerVersions === null) return [];
        return $containerVersions;
    }

    /**
     * @param MasterContainer $container
     *
     * @return bool|MasterContainer
     */
    public function getPublishedContainer(MasterContainer $container, $publish = false) {
        /** @var ContainerVersion $latestContainerVersion */
        $latestContainerVersion = $this->versionsRepository->getPublishedContainerVersion($container);
        if ($latestContainerVersion === null) return false;
        $versionData = stream_get_contents($latestContainerVersion->getVersionData());
        $savedContainer = new MasterContainer();
        $savedContainer->unserialize($versionData);
        return $savedContainer;
    }

    /**
     * @param MasterContainer $container
     *
     * @return MasterContainer
     */
    public function saveContainer(MasterContainer $container) {
        $savedContainer = new ContainerVersion($container);
        $this->em->persist($savedContainer);
        $this->em->flush($savedContainer);
        $container->addVesion($savedContainer);
        return $container;
    }

}