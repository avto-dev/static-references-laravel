<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\Entities;

class CadastralDistrict implements EntityInterface
{
    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array<int, CadastralArea>
     */
    protected $areas = [];

    /**
     * Create a new entity instance.
     *
     * @param int             $code
     * @param string          $name
     * @param CadastralArea[] $areas
     */
    public function __construct(int $code, string $name, array $areas)
    {
        $this->code = $code;
        $this->name = $name;

        foreach ($areas as $area) {
            $this->areas[$area->getCode()] = $area;
        }
    }

    /**
     * Get cadastral district code.
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Get cadastral district name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get cadastral areas, included into this district.
     *
     * @return CadastralArea[]
     */
    public function getAreas(): array
    {
        return \array_values($this->areas);
    }

    /**
     * Cadastral area by code is exists?
     *
     * @param int $code
     *
     * @return bool
     */
    public function hasAreaWithCode(int $code): bool
    {
        return isset($this->areas[$code]);
    }

    /**
     * Get the instance as an array.
     *
     * @return array{code:int, name:string}
     */
    public function toArray(): array
    {
        return [
            'code'  => $this->code,
            'name'  => $this->name,
            'areas' => \array_map(static function (CadastralArea $area): array {
                return $area->toArray();
            }, \array_values($this->areas)),
        ];
    }
}
