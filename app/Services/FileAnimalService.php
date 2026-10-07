<?php

namespace App\Services;

use App\Contracts\AnimalServiceInterface;
use Illuminate\Support\Facades\Storage;

class FileAnimalService implements AnimalServiceInterface
{
    private const FILE = 'animals.json';

    private function seedData(): array
    {
        return [
            ['id' => 1, 'name' => 'Leo', 'species' => 'Leon', 'age' => 5],
            ['id' => 2, 'name' => 'Dora', 'species' => 'Elefante', 'age' => 10],
            ['id' => 3, 'name' => 'Nemo', 'species' => 'Pez Payaso', 'age' => 2],
        ];
    }

    private function getAnimals(): array
    {
        if (! Storage::disk('local')->exists(self::FILE)) {
            $this->saveAnimals($this->seedData());
        }

        $animals = json_decode(Storage::disk('local')->get(self::FILE), true);

        return is_array($animals) ? $animals : [];
    }

    private function saveAnimals(array $animals): void
    {
        Storage::disk('local')->put(
            self::FILE,
            json_encode(array_values($animals), JSON_PRETTY_PRINT)
        );
    }

    public function all(): array
    {
        return $this->getAnimals();
    }

    public function find(int|string $id): array
    {
        foreach ($this->getAnimals() as $animal) {
            if ((string) $animal['id'] === (string) $id) {
                return $animal;
            }
        }

        throw new \Exception("El animal con ID {$id} no fue encontrado.");
    }

    public function create(array $data): array
    {
        $animals = $this->getAnimals();

        $nextId = empty($animals) ? 1 : max(array_column($animals, 'id')) + 1;
        $data['id'] = $nextId;
        $animals[] = $data;

        $this->saveAnimals($animals);

        return $data;
    }

    public function update(int|string $id, array $data): bool
    {
        $animals = $this->getAnimals();
        $found = false;

        foreach ($animals as &$animal) {
            if ((string) $animal['id'] === (string) $id) {
                $animal = array_merge($animal, $data);
                $found = true;
                break;
            }
        }
        unset($animal);

        if (! $found) {
            throw new \Exception("El animal con ID {$id} no fue encontrado.");
        }

        $this->saveAnimals($animals);

        return true;
    }

    public function delete(int|string $id): bool
    {
        $animals = $this->getAnimals();
        $filtered = array_values(array_filter(
            $animals,
            fn ($animal) => (string) $animal['id'] !== (string) $id
        ));

        if (count($filtered) === count($animals)) {
            throw new \Exception("El animal con ID {$id} no fue encontrado.");
        }

        $this->saveAnimals($filtered);

        return true;
    }

    public function reset(): void
    {
        $this->saveAnimals($this->seedData());
    }
}