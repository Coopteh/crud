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

    public function store(array $data): Record
    {
        $record = new Record();
        $record->title = $data['title'];
        $record->content = $data['content'];
        $record->save();
        return $record;
    }

    public function update(int $id, array $data): ?Record
    {
        $record = Record::find($id);
        if (!$record) {
            return null;
        }

        $record->title = $data['title'] ?? $record->title;
        $record->content = $data['content'] ?? $record->content;
        $record->save();
        return $record;
    }

    public function destroy(int $id): bool
    {
        $record = Record::find($id);
        return $record ? $record->delete() : false;
    }
}
