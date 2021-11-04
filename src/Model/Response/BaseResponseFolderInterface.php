<?php

declare(strict_types=1);

namespace App\Model\Response;

interface BaseResponseFolderInterface
{
    public function getUserDossierId(): ?int;
    public function getPartenaireDossierId(): ?string;
    public function getPrenom(): ?string;
    public function getNom(): ?string;
}
