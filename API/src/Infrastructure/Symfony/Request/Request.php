<?php

namespace App\Infrastructure\Symfony\Request;

use App\Exception\ValidationException;
use App\Error\Validation\ValidationError;
use App\Error\Validation\ValidationErrorsList;
use App\Exception\Exists\Exists;
use App\Query\CheckUniqueInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\Validator\Constraints\All;

abstract class Request
{
    private const PUT = 'PUT';

    private SymfonyRequest $httpRequest;

    private ValidatorInterface $validator;

    /**
     * @var array<string, string>
     */
    private array $parameters = [];

    public function __construct(RequestStack $stack, ValidatorInterface $validator, private readonly CheckUniqueInterface $uniqueQuery)
    {

        $request = $stack->getCurrentRequest();
        if (is_null($request)) {
            throw new InvalidArgumentException('Request does not exist.');
        }

        $method = $request->getMethod();

        $this->httpRequest = $request;
        $this->validator = $validator;

        $this->mergeAllParameters();
        $this->validateUnique($method);
        $this->validate();
    }

    abstract protected function rules(): Collection | All;

    abstract protected function getTableName(): string;
    /**
     * @return array<string, string>
     */

    protected function getUnique(): array
    {
        return [];
    }

    protected function parameters(): array
    {
        return $this->parameters;
    }

    protected function getArrayParameter(string $parameterName): array
    {
        return $this->parameterExist($parameterName) ? (array)$this->parameters[$parameterName] : [];
    }

    protected function getParameter(string $parameterName): ?string
    {
        return $this->parameterExist($parameterName) && null !== $this->parameters[$parameterName] ?
            (string)$this->parameters[$parameterName] : null;
    }

    protected function parameterExist(string $parameterName): bool
    {
        return array_key_exists($parameterName, $this->parameters);
    }

    private function mergeAllParameters(): void
    {
        $requestQueryData = $this->httpRequest->query->all();
        $requestPostData = $this->httpRequest->request->all();
        $requestBodyData = json_decode((string) $this->httpRequest->getContent(), true) ?? [];

        $this->parameters = array_merge($requestQueryData, $requestPostData, $requestBodyData);
    }

    /**
     * @throws ValidationException
     */
    private function validate(): void
    {
        $violations = $this->validator->validate($this->parameters(), $this->rules());

        if ($violations->count()) {
            throw new ValidationException($this->getErrorsList($violations));
        }
    }

    private function getErrorsList(ConstraintViolationListInterface $violations): ValidationErrorsList
    {
        $errorsList = new ValidationErrorsList();

        foreach ($violations as $violation) {
            $errorsList->add(
                new ValidationError(
                    $this->getPropertyName($violation->getPropertyPath()),
                    $violation->getMessage()
                )
            );
        }

        return $errorsList;
    }

    private function getPropertyName(string $property): string
    {
        return substr($property, 1, -1);
    }

    private function validateUnique(string $method): void
    {
        $unique = $this->getUnique();
        $tableName = $this->getTableName();
        $guid = (self::PUT === $method ) ? $this->httpRequest->attributes->get('guid') : null;
        $queryData = $this->uniqueQuery->checkUnique($unique, $tableName, $guid);
        $this->check($queryData, $unique, $tableName);
    }

    private function check(array $queryData, array $unique, string $tableName): void
    {
        $requestBodyData = $this->parameters();
        foreach ($queryData as $element) {
            foreach ($unique as $param) {
                if (in_array($requestBodyData[$param], $element)) {
                    throw new Exists($requestBodyData[$param], substr($tableName, 0, -1), $param);
                }
            }
        }
    }
}
