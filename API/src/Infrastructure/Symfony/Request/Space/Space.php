<?php

namespace App\Infrastructure\Symfony\Request\Space;

use App\DTO\Dimensions;
use App\DTO\Position;
use App\DTO\RequestParams\Collection\DesksParams;
use App\DTO\RequestParams\DeskParams;
use App\DTO\RequestParams\SpaceParams;
use App\Infrastructure\Symfony\Request\NameRequirements;
use App\Infrastructure\Symfony\Request\Request;
use App\Request\Field\Dimension;
use Symfony\Component\Validator\Constraints\Collection;
use App\Request\Space\Space as SpaceRequestInteface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Count;

class Space extends Request implements SpaceRequestInteface
{
    public function params(): SpaceParams
    {
        $dimensionsData = $this->getArrayParameter(Dimension::FIELD_DIMENSION);
        $dimensionsObject = new Dimensions($dimensionsData['width'], $dimensionsData['height']);

        $desksData = $this->getArrayParameter(self::FIELD_DESKS);
        $desksObject = new DesksParams(...array_map(fn(array $deskData) => new DeskParams(
            $deskData['name'],
            new Position($deskData['position']['x'], $deskData['position']['y'], $deskData['position']['angle']),
            $deskData['spaceGuid'] ?? null
        ), $desksData));

        return new SpaceParams(
            $this->getParameter(self::FIELD_NAME),
            $dimensionsObject,
            $desksObject
        );
    }

    protected function rules(): Collection
    {
        return new Collection([
        self::FIELD_NAME => [
            new NameRequirements(),
        ],
        Dimension::FIELD_DIMENSION => [
            new Collection([
                'width' => [
                    new Type(['type' => 'numeric']),
                ],
                'height' => [
                    new Type(['type' => 'numeric']),
                ],
            ]),
        ],
        self::FIELD_DESKS => [
            new Count(['min' => 1]),
            new All([
                'constraints' => [
                    new Collection([
                        'name' => [
                            new Type(['type' => 'string']),
                        ],
                        'position' => [
                            new Collection([
                                'x' => [
                                    new Type(['type' => 'numeric']),
                                ],
                                'y' => [
                                    new Type(['type' => 'numeric']),
                                ],
                                'angle' => [
                                    new Type(['type' => 'numeric']),
                                ],
                            ]),
                        ],
                    ]),
                ],
            ]),
        ],
        ]);
    }

    protected function getUnique(): array
    {
        return ['name'];
    }

    protected function getTableName(): string
    {
        return 'spaces';
    }
}
