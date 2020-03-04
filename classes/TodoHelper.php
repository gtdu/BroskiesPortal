<?php

/**
* Class to manage all the todo related actions
*
* @author Ryan Cobelli <ryan.cobelli@gmail.com>
*/
class TodoHelper extends Helper
{
    public function getTodoByID($resource_id)
    {
        if (empty($resource_id)) {
            $this->error = "All fields are required";
            return false;
        }

        $handle = $this->conn->prepare('SELECT * FROM `todo` WHERE id = ? LIMIT 1');
        $handle->bindValue(1, $resource_id);
        if ($handle->execute()) {
            return $handle->fetchAll(\PDO::FETCH_ASSOC)[0];
        } else {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
    }

    public function getTodos()
    {
        $handle = $this->conn->prepare('SELECT * FROM `todo` ORDER BY title');
        if ($handle->execute()) {
            return $handle->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
    }

    public function getUncompletedTodos($user_id) {
        if (empty($user_id)) {
            $this->error = "All fields are required";
            return false;
        }

        $handle = $this->conn->prepare('SELECT id, title, link, description FROM `todo` WHERE id NOT IN (SELECT todo_id FROM `todoCompletions` WHERE user_id = ?)');
        $handle->bindValue(1, $user_id);
        if ($handle->execute()) {
            return $handle->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
    }

    public function markTodoAsCompleted($todo_id, $user_id) {
        if (empty($todo_id) || empty($user_id)) {
            $this->error = "All fields are required";
            return false;
        }

        $handle = $this->conn->prepare('INSERT INTO `todoCompletions` (todo_id, user_id) VALUES (?, ?)');
        $handle->bindValue(1, $todo_id);
        $handle->bindValue(2, $user_id);
        if ($handle->execute()) {
            return true;
        } else {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
    }

    public function deleteTodo($todo_id)
    {
        if (empty($todo_id)) {
            $this->error = "All fields are required";
            return false;
        }

        $handle = $this->conn->prepare('DELETE FROM `todo` WHERE id = ?; DELETE FROM `todoCompletions` WHERE todo_id = ?');
        $handle->bindValue(1, $todo_id);
        $handle->bindValue(2, $todo_id);
        if ($handle->execute()) {
            return true;
        } else {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
    }

    public function createTodo($title, $link, $description)
    {
        if (empty($title)) {
            $this->error = "All fields are required";
            return false;
        }

        $handle = $this->conn->prepare('INSERT INTO `todo` (title, link, description) VALUES (?, ?, ?)');
        $handle->bindValue(1, $title);
        $handle->bindValue(2, $link);
        $handle->bindValue(3, $description);
        if ($handle->execute()) {
            return true;
        } else {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
    }

    public function updateTodo($todo_id, $title, $link, $description)
    {
        if (empty($todo_id) || empty($title)) {
            $this->error = "All fields are required";
            return false;
        }

        $handle = $this->conn->prepare('UPDATE `todo` SET title = ?, link = ?, description = ? WHERE id = ?');
        $handle->bindValue(1, $title);
        $handle->bindValue(2, $link);
        $handle->bindValue(3, $description);
        $handle->bindValue(4, $todo_id);
        if ($handle->execute()) {
            return true;
        } else {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
    }

    public function renderTodos($user_id, $user_level, $all = false, $home = false) {
        if ($all) {
            $todos = $this->getTodos($user_id);
        } else {
            $todos = $this->getUncompletedTodos($user_id);
        }

        if (count($todos) == 0) {
            echo "<h3>Nothing Left To-Do</h3>";
        } else {
            ?>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 25%">Title</th>
                        <th style="width: 30%">Link</th>
                        <th style="width: 35%">Description</th>
                        <th style="width: 10%">&nbsp;</th>
                    </tr>
                </thead>
            <?php
            foreach ($todos as $resource) {
                echo "<tr>";
                echo "<td>" . $resource['title'] . "</td>";
                if (empty($resource['link'])) {
                    echo "<td></td>";
                } else {
                    echo "<td><a href='" . $resource['link'] . "' target='_blank'>" . substr($resource['link'], 0, 30) . "...</a></td>";
                }
                echo "<td>" . $resource['description'] . "</td>";
                echo "<td>";
                echo '<a href="todo.php?action=completed&id=' . $resource['id'] . '&home=' . $home . '"><img src="../resources/completed.png" class="icon" title="Mark as Completed"></a>';
                if ($user_level > 1) {
                    echo '<a href="todo.php?action=delete&id=' . $resource['id'] . '&home=' . $home . '"><img src="../resources/delete.png" class="icon"></a><a href="todo.php?action=edit&id=' . $resource['id'] . '"><img src="../resources/edit.png" class="icon"></a>';
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }
}
