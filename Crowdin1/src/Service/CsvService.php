<?php
namespace App\Service;

use App\Entity\Sources;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CsvService
{
    public function handleCsvImport(UploadedFile $file, int $projectId, EntityManagerInterface $em): void
    {
        $csvData = file_get_contents($file->getPathname());
        $rows = array_map('str_getcsv', explode("\n", $csvData));
        $header = array_shift($rows);

        foreach ($rows as $row) {
            if (count($row) < 2) {
                continue;
            }

            $source = new Sources();
            $source->setClef($row[0]);
            $source->setContenu($row[1]);
            $source->setCreatedAt(new \DateTimeImmutable());
            $source->setUpdatedAt(new \DateTimeImmutable());
            $source->setProjectId($projectId);

            $em->persist($source);
        }

        $em->flush();
    }
}
