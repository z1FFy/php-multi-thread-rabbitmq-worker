<?php


namespace App\Task;


class TaskDto
{
    /** @var string */
    public string $category;

    /** @var string */
    public string $task;

    /** @var TaskDataDto */
    public TaskDataDto $data;
}