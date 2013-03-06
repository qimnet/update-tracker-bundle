<?php
namespace Qimnet\UpdateTrackerBundle\Tests\UpdateTracker;
use Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateTrackerRepository;
use Qimnet\UpdateTrackerBundle\Tests\SchemaTestCase;
use Qimnet\UpdateTrackerBundle\UpdateTracker\DoctrineEventSubscriber;

class UpdateTrackerRepositoryTest  extends SchemaTestCase
{
    const CLASS_NAME='Qimnet\UpdateTrackerTestBundle\Entity\UpdateTrackerTest';
    const ENTITY_NAME='QimnetUpdateTrackerTestBundle:UpdateTrackerTest';
    
    static $updates=array();


    public function testConstructByEntityName()
    {
        return new UpdateTrackerRepository(self::ENTITY_NAME);;
    }
    public function testConstructByClassName()
    {
        return new UpdateTrackerRepository(self::CLASS_NAME);
    }

    /**
     * @depends testConstructByEntityName
     */
    public function testGetEntityName(UpdateTrackerRepository $repository)
    {
        $this->assertEquals(self::ENTITY_NAME, $repository->getEntityName());
    }
    /**
     * @depends testConstructByEntityName
     */
    public function testGetEntityRepository(UpdateTrackerRepository $repository)
    {
        $entityRepository = $repository->getEntityRepository(self::$entityManager);
        $this->assertInstanceOf('Doctrine\ORM\EntityRepository', $entityRepository);
        $this->assertEquals(self::CLASS_NAME, $entityRepository->getClassName());
        return $entityRepository;
    }
    /**
     * @depends testConstructByEntityName
     */
    public function testAddEventListener(UpdateTrackerRepository $repository)
    {
        $listener = $this->getMock('Qimnet\UpdateTrackerBundle\UpdateTracker\UpdateListenerInterface');
        $repository->addEventListener($listener);
        return $repository;
    }
    public function getNamespaces()
    {
        return array(
            array('namespace1'), 
            array('namespace2'),
            array(array('namespace1', 'namespace2', 'namespace3')),
            array(array('test', 'global')),
            array(array('test42')),
            array(array('global')));
    }
    /**
     * @depends testConstructByEntityName
     * @dataProvider getNamespaces
     */
    public function testMarkUpdated($namespaces)
    {
        $repository = new UpdateTrackerRepository(self::ENTITY_NAME);
        $updates = $repository->markUpdated(self::$entityManager, $namespaces);
        foreach($updates as $update)
        {
            self::$updates[$update->getName()] = $update;
            self::$entityManager->persist($update);
        }
        self::$entityManager->flush();
        return $repository;
    }
    /**
     * @depends testMarkUpdated
     */
    public function testGetLastUpdate()
    {
        $repository = new UpdateTrackerRepository(self::ENTITY_NAME);
        foreach(self::$updates as $namespace=>$update)
        {
            $this->assertEquals($update->getDate(),$repository->getLastUpdate(self::$entityManager, $namespace));
        }
        $this->assertEquals(self::$updates['global']->getDate(),$repository->getLastUpdate(self::$entityManager, 'bogus'));
        $this->assertNull($repository->getLastUpdate(self::$entityManager, 'bogus',false));
    }
}

?>