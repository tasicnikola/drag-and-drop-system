<?php

namespace App\Infrastructure\Symfony\Request\Desk;

use App\DTO\Position;
use App\DTO\RequestParams\DeskParams;
use App\Request\Desk\Desk as DeskRequestParams;
use App\Infrastructure\Symfony\Request\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

class Desk extends Request implements DeskRequestParams
{
    public function params(): DeskParams
    {
        $positionData = $this->getArrayParameter(self::FIELD_POSITION);
        $positionObject = new Position($positionData['x'], $positionData['y'], $positionData['angle']);

        return new DeskParams(
            $this->getParameter(self::FIELD_NAME),
            $positionObject,
            $this->getParameter(self::FIELD_SPACE_GUID)
        );
    }
    protected function getTableName(): string
    {
        return 'desks';
    }

    protected function getUnique(): array
    {
        return ['name'];
    }

    protected function rules(): Collection
    {
        return new Collection([
            self::FIELD_NAME => [
                new Type('string'),
                new Length(min:2, max:64)
            ],
            self::FIELD_POSITION => [
                new Collection([
                    'x' => [
                        new Type('numeric'),
                    ],
                    'y' => [
                        new Type('numeric'),
                    ],
                    'angle' => [
                        new Type('numeric'),
                    ],
                ])
            ],
            self::FIELD_SPACE_GUID => [
                new Uuid()
            ],
        ]);
    }
}
