<?php

/**
* Class to manage all the resources related actions
*
* @author Ryan Cobelli <ryan.cobelli@gmail.com>
*/
class ResourcesHelper extends Helper
{
    public function getResourceByID($resource_id)
    {
        if (empty($resource_id)) {
            $this->error = "All fields are required";
            return false;
        }

        $handle = $this->conn->prepare('SELECT * FROM `resources` WHERE id = ? LIMIT 1');
        $handle->bindValue(1, $resource_id);
        if ($handle->execute()) {
            return $handle->fetchAll(\PDO::FETCH_ASSOC)[0];
        } else {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
    }

    public function getResources($vis_level)
    {
        if (empty($vis_level)) {
            $this->error = "All fields are required";
            return false;
        }

        $handle = $this->conn->prepare('SELECT * FROM `resources` WHERE visibility <= ? ORDER BY position, title');
        $handle->bindValue(1, $vis_level);
        if ($handle->execute()) {
            return $handle->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
    }

    public function deleteResource($todo_id)
    {
        if (empty($todo_id)) {
            $this->error = "All fields are required";
            return false;
        }

        $handle = $this->conn->prepare('DELETE FROM `resources` WHERE id = ?');
        $handle->bindValue(1, $todo_id);
        if ($handle->execute()) {
            return true;
        } else {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
    }

    public function createResource($title, $link, $description, $position, $visibility)
    {
        if (empty($title) || empty($link) || empty($position) || empty($visibility)) {
            $this->error = "All fields are required";
            return false;
        }

        $handle = $this->conn->prepare('INSERT INTO `resources` (title, link, description, position, visibility) VALUES (?, ?, ?, ?, ?)');
        $handle->bindValue(1, $title);
        $handle->bindValue(2, $link);
        $handle->bindValue(3, $description);
        $handle->bindValue(4, $position);
        $handle->bindValue(5, $visibility);
        if ($handle->execute()) {
            return true;
        } else {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
    }

    public function updateResource($resource_id, $title, $link, $description, $position, $visibility)
    {
        if (empty($resource_id) || empty($title) || empty($link) || empty($position) || empty($visibility)) {
            $this->error = "All fields are required";
            return false;
        }

        $handle = $this->conn->prepare('UPDATE `resources` SET title = ?, link = ?, description = ?, position = ?, visibility = ? WHERE id = ?');
        $handle->bindValue(1, $title);
        $handle->bindValue(2, $link);
        $handle->bindValue(3, $description);
        $handle->bindValue(4, $position);
        $handle->bindValue(5, $visibility);
        $handle->bindValue(6, $resource_id);
        if ($handle->execute()) {
            return true;
        } else {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
    }

    public function renderResources($user_level) {
        $resources = $this->getResources($user_level);

        if (count($resources) == 0) {
            echo "<h3>No Resources Found</h3>";
        } else {
            ?>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 15%">Position</th>
                        <th style="width: 20%">Title</th>
                        <th style="width: 30%">Link</th>
                        <th style="width: 35%">Description</th>
                        <?php
                        if ($user_level > 1) {
                            echo '<th>&nbsp;</th>';
                        }
                        ?>
                    </tr>
                </thead>
            <?php
            foreach ($resources as $resource) {
                echo "<tr>";
                echo "<td>" . $resource['position'] . "</td>";
                echo "<td>" . $resource['title'] . "</td>";
                echo "<td><a href='" . $resource['link'] . "' target='_blank'>" . substr($resource['link'], 0, 40) . "...</a></td>";
                echo "<td>" . $resource['description'] . "</td>";
                if ($user_level > 1) {
                    echo '<td><a href="resources.php?action=delete&id=' . $resource['id'] . '"><img src="../resources/delete.png" class="icon"></a><a href="resources.php?action=edit&id=' . $resource['id'] . '"><img src="../resources/edit.png" class="icon"></a></td>';
                }
                echo "</tr>";
            }
            echo "</table>";
        }
    }
}
