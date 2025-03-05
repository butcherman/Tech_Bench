<?php

namespace App\Enums;

/*
|-------------------------------------------------------------------------------
| ContainerList is a list of all Tech Bench Docker Containers.
|-------------------------------------------------------------------------------
*/

enum ContainerList: string
{
    case nginx = 'nginx';
    case reverb = 'reverb';
    case horizon = 'horizon';
    case scheduler = 'scheduler';
    case database = 'database';
    case redis = 'redis';
    case meilisearch = 'meilisearch';
    case tech_bench = 'tech_bench';
}
