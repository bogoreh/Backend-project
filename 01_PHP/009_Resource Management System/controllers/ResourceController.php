<?php
class ResourceController {
    private $resourceModel;

    public function __construct($db) {
        $this->resourceModel = new Resource($db);
    }

    public function index() {
        $stmt = $this->resourceModel->read();
        $resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resources;
    }

    public function create($data) {
        $this->resourceModel->name = $data['name'];
        $this->resourceModel->type = $data['type'];
        $this->resourceModel->quantity = $data['quantity'];
        $this->resourceModel->status = $data['status'];
        $this->resourceModel->description = $data['description'];

        if($this->resourceModel->create()) {
            return true;
        }
        return false;
    }

    public function getResource($id) {
        $this->resourceModel->id = $id;
        if($this->resourceModel->readOne()) {
            return $this->resourceModel;
        }
        return null;
    }

    public function update($id, $data) {
        $this->resourceModel->id = $id;
        $this->resourceModel->name = $data['name'];
        $this->resourceModel->type = $data['type'];
        $this->resourceModel->quantity = $data['quantity'];
        $this->resourceModel->status = $data['status'];
        $this->resourceModel->description = $data['description'];

        if($this->resourceModel->update()) {
            return true;
        }
        return false;
    }

    public function delete($id) {
        $this->resourceModel->id = $id;
        if($this->resourceModel->delete()) {
            return true;
        }
        return false;
    }
}
?>