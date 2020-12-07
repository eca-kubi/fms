<?php


namespace VisitorAccessForm\Entities;


use GenericEntity;
use VisitorAccessForm\AccessLevelCollection;
use VisitorAccessForm\AccessLevelTrait;

class AccessLevel extends GenericEntity
{
    use AccessLevelTrait;

    public const SITE_MAIN_ENTRANCE = 'Site Main Entrance';
    public const MINING_ADMIN_BLOCK = 'Mining Admin Block';
    public const PROCESS_PLANT  = 'Process Plant (High Security Area)';
    public const GENERAL_SITE = 'General Site';

    public static function getDefaultAreas()
    {
        return [self::SITE_MAIN_ENTRANCE, self::MINING_ADMIN_BLOCK, self::PROCESS_PLANT, self::GENERAL_SITE];
    }

    public static function getDefaultAccessLevels(?int $visitor_access_form_id=null) {
        $accessLevels = [];
        foreach (self::getDefaultAreas() as $defaultArea) {
            $accessLevels[] = new AccessLevel(['area' => $defaultArea, 'visitor_access_form_id' => $visitor_access_form_id]);
        }
        return new AccessLevelCollection(...$accessLevels);
    }
}
