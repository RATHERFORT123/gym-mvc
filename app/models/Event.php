<?php

class Event extends Model
{
    /**
     * Get all events
     */
    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM events ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all active events
     */
    public function getActive()
    {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE status = 'active' ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get event by ID
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create new event
     */
    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO events (title, description, status) VALUES (?, ?, ?)"
        );
        return $stmt->execute([
            $data['title'],
            $data['description'] ?? null,
            $data['status'] ?? 'inactive'
        ]);
    }

    /**
     * Update event
     */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare(
            "UPDATE events SET title = ?, description = ?, status = ? WHERE id = ?"
        );
        return $stmt->execute([
            $data['title'],
            $data['description'] ?? null,
            $data['status'] ?? 'inactive',
            $id
        ]);
    }

    /**
     * Toggle event status
     */
    public function toggleStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE events SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    /**
     * Delete event
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM events WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Count total events
     */
    public function count()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM events");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}
