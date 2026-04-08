<?php

class RecordController
{
    public function index(): array
    {
        return Record::all();
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

    public function delete(int $id): bool
    {
        $record = Record::find($id);
        return $record ? $record->delete() : false;
    }
}
