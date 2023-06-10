<?php

namespace Tests\Unit\Service;

use App\DTO\Collection\Desks;
use App\DTO\Desk as DeskDTO;
use App\DTO\Dimensions;
use App\DTO\Position;
use App\DTO\Space as SpaceDTO;
use App\DTO\RequestParams\DeskParams;
use App\Entity\Desk;
use App\Entity\Space;
use App\Exception\NotFound\DeskNotFoundException;
use App\Exception\NotFound\SpaceNotFoundException;
use App\Query\DeskInterface;
use App\Repository\DeskRepository;
use App\Repository\SpaceRepository;
use App\Service\DeskService;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Unit\BaseTest;

class DeskServiceTest extends BaseTest
{
    private const GUID = '7fe48a1a-3bb9-441d-a3dd-2fe50bddfeaf';

    private MockObject|DeskRepository|null $deskRepositoryMock;
    private MockObject|SpaceRepository|null $spaceRepositoryMock;
    private MockObject|DeskInterface|null $deskQueryMock;
    private MockObject|Desk|null $deskEntityMock;
    private MockObject|Space|null $spaceEntityMock;
    private DeskService $deskService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->deskRepositoryMock = $this->createMock(DeskRepository::class);
        $this->spaceRepositoryMock = $this->createMock(SpaceRepository::class);
        $this->deskQueryMock = $this->createMock(DeskInterface::class);
        $this->deskEntityMock = $this->createMock(Desk::class);
        $this->spaceEntityMock = $this->createMock(Space::class);
        $this->deskService = new DeskService(
            $this->deskRepositoryMock,
            $this->spaceRepositoryMock,
            $this->deskQueryMock
        );
    }

    protected function tearDown(): void
    {
        $this->deskRepositoryMock = null;
        $this->spaceRepositoryMock = null;
        $this->deskQueryMock = null;
        $this->deskEntityMock = null;
        $this->spaceEntityMock = null;
        $this->deskService = null;

        parent::tearDown();
    }

    public static function dataProvider(): array
    {
        return [
            [
                new DeskParams('desk', new Position(1, 1, 1), self::GUID)
            ]
        ];
    }

    public function testGetAll(): void
    {
        $desks = $this->createDesksCollectionMock();

        $this->deskQueryMock
            ->expects($this->once())
            ->method(self::GET_ALL)
            ->willReturn($desks);

        $this->assertEquals($desks, $this->deskService->getAll());
    }

    public function testGet(): void
    {
        $deskDTO = $this->createDTOMock();

        $this->deskQueryMock
            ->expects($this->once())
            ->method(self::GET_BY_GUID)
            ->with(self::GUID)
            ->willReturn($deskDTO);

        $this->assertEquals($deskDTO, $this->deskService->get(self::GUID));
    }

    public function testGetWithDeskNotFoundException(): void
    {
        $this->deskQueryMock
            ->expects($this->once())
            ->method(self::GET_BY_GUID)
            ->with(self::GUID)
            ->willReturn(null);

        $this->expectException(DeskNotFoundException::class);
        $this->deskService->get(self::GUID);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCreate(DeskParams $deskParams): void
    {
        $this->spaceRepositoryMock
            ->expects($this->once())
            ->method(self::FIND)
            ->willReturn($this->spaceEntityMock);

        $this->deskRepositoryMock
            ->expects($this->once())
            ->method(self::GET_ENTITY_INSTANCE)
            ->willReturn($this->deskEntityMock);

        $this->deskEntityMock
            ->expects($this->once())
            ->method('update')
            ->with($deskParams, $this->spaceEntityMock);

        $this->deskRepositoryMock
            ->expects($this->once())
            ->method(self::SAVE)
            ->with($this->deskEntityMock);

        $this->deskEntityMock
            ->expects($this->once())
            ->method(self::GET_GUID)
            ->willReturn(self::GUID);

        $this->assertMatchesRegularExpression(
            self::GUID_EXAMPLE,
            $this->deskService->create($deskParams)
        );
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCreateWithSpaceNotFoundException(DeskParams $deskParams): void
    {
        $this->spaceRepositoryMock
            ->expects($this->once())
            ->method(self::FIND)
            ->willReturn(null);

        $this->expectException(SpaceNotFoundException::class);
        $this->deskService->create($deskParams);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testUpdate(DeskParams $deskParams): void
    {
        $this->deskRepositoryMock
            ->expects($this->once())
            ->method(self::FIND)
            ->willReturn($this->deskEntityMock);

        $this->spaceRepositoryMock
            ->expects($this->once())
            ->method(self::FIND)
            ->willReturn($this->spaceEntityMock);

        $this->deskEntityMock
            ->expects($this->once())
            ->method('update')
            ->with($deskParams, $this->spaceEntityMock);

        $this->deskRepositoryMock
            ->expects($this->once())
            ->method(self::SAVE)
            ->with($this->deskEntityMock);

        $this->deskService->update($deskParams, self::GUID);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testUpdateWithDeskNotFoundException(DeskParams $deskParams): void
    {
        $this->deskRepositoryMock
            ->expects($this->once())
            ->method(self::FIND)
            ->with(self::GUID)
            ->willReturn(null);

        $this->expectException(DeskNotFoundException::class);
        $this->deskService->update($deskParams, self::GUID);
    }

     /**
     * @dataProvider dataProvider
     */
    public function testUpdateWithSpaceNotFoundException(DeskParams $deskParams): void
    {
        $this->deskRepositoryMock
            ->expects($this->once())
            ->method(self::FIND)
            ->with(self::GUID)
            ->willReturn($this->deskEntityMock);


        $this->spaceRepositoryMock
            ->expects($this->once())
            ->method(self::FIND)
            ->willReturn(null);

        $this->expectException(SpaceNotFoundException::class);
        $this->deskService->update($deskParams, self::GUID);
    }

    public function testDelete(): void
    {
        $this->deskRepositoryMock
            ->expects($this->once())
            ->method(self::FIND)
            ->with(self::GUID)
            ->willReturn($this->deskEntityMock);

        $this->deskRepositoryMock
            ->expects($this->once())
            ->method(self::REMOVE)
            ->with($this->deskEntityMock);

        $this->deskService->delete(self::GUID);
    }

    public function testDeleteWithDeskNotFoundException(): void
    {
        $this->deskRepositoryMock
            ->expects($this->once())
            ->method(self::FIND)
            ->with(self::GUID)
            ->willReturn(null);

        $this->expectException(DeskNotFoundException::class);
        $this->deskService->delete(self::GUID);
    }

    private function createDesksCollectionMock(): Desks
    {
        return new Desks(
            [
                [
                    'guid' => '7fe48a1a-3bb9-441d-a3dd-2fe50bddfeaf',
                    'name' => 'desk1',
                    'position' => 'position1',
                    'space' => 'Space1',
                    'created' => '2023-06-02 16:19:55+01',
                    'updatedAt' => '2023-06-03 12:03:21+01',
                ],
                [
                    'guid' => '543ae116-d548-4fdd-bbbc-fb28e7a1995d',
                    'name' => 'desk2',
                    'position' => 'position2',
                    'space' => 'Space2',
                    'created' => '2023-06-02 16:19:55+01',
                    'updatedAt' => '2023-06-03 12:03:21+01',
                ],
            ]
        );
    }

    private function createDTOMock(): DeskDTO
    {
        return new DeskDTO(
            self::GUID,
            'desk',
            new Position(
                1,
                1,
                1
            ),
            new SpaceDTO(
                '7fe48a1a-3bb9-441d-a3dd-2fe50bddfeaf',
                'Space0',
                new Dimensions(100, 100),
                new DateTimeImmutable('now'),
                null
            ),
            new DateTimeImmutable('now'),
            null
        );
    }
}
