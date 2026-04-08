<?php

class RecordController
{
    private const PER_PAGE = 10;

    public function index(): array
    {
        return Record::all();
    }

    public function paginate(int $page = 1): array
    {
        return Record::paginate($page, self::PER_PAGE);
    }

    public function totalPages(): int
    {
        return (int)ceil(Record::count() / self::PER_PAGE);
    }

    public function show(int $id): ?Record
    {
        return Record::find($id);
    }

    public function insert(array $data): Record
    {
        $record = new Record();
        $record->name = $data['name'];
        $record->save();
        return $record;
    }

    public function update(int $id, array $data): ?Record
    {
        $record = Record::find($id);
        if (!$record) return null;

        $record->name = $data['name'] ?? $record->name;
        $record->save();
        return $record;
    }

    public function toggleDelete(int $id): ?Record
    {
        $record = Record::find($id);
        if (!$record) return null;
        $record->toggleDelete();
        return $record;
    }

    public function forceDelete(int $id): bool
    {
        $record = Record::find($id);
        return $record ? $record->forceDelete() : false;
    }
}