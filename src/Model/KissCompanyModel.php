<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Model;

use Contao\Model;
use Contao\Model\Collection;

/**
 * @property integer $id
 * @property integer $tstamp
 * @property string  $name
 * @property string  $logo
 * @property string  $street
 * @property string  $postal
 * @property string  $city
 * @property string  $state
 * @property string  $country
 * @property string  $opening_times
 * @property string  $closing_times
 * @property string  $phone_numbers
 * @property string  $emails
 * @property string  $websites
 * @property string  $fax_numbers
 * @property string  $socials
 * @property string  $additional
 *
 * @method static KissCompanyModel|null findById($id, array $opt=[])
 * @method static KissCompanyModel|null findByPk($id, array $opt=[])
 * @method static KissCompanyModel|null findOneBy($col, $val, array $opt=[])
 * @method static KissCompanyModel|null findOneByTstamp($val, array $opt=[])
 * @method static KissCompanyModel|null findOneByName($val, array $opt=[])
 * @method static KissCompanyModel|null findOneByLogo($val, array $opt=[])
 * @method static KissCompanyModel|null findOneByStreet($val, array $opt=[])
 * @method static KissCompanyModel|null findOneByPostal($val, array $opt=[])
 * @method static KissCompanyModel|null findOneByCity($val, array $opt=[])
 * @method static KissCompanyModel|null findOneByState($val, array $opt=[])
 * @method static KissCompanyModel|null findOneByCountry($val, array $opt=[])
 * @method static KissCompanyModel|null findOneByOpening_times($val, array $opt=[])
 * @method static KissCompanyModel|null findOneByClosing_times($val, array $opt=[])
 * @method static KissCompanyModel|null findOneByPhone_numbers($val, array $opt=[])
 * @method static KissCompanyModel|null findOneByEmails($val, array $opt=[])
 * @method static KissCompanyModel|null findOneByWebsites($val, array $opt=[])
 * @method static KissCompanyModel|null findOneByFax_numbers($val, array $opt=[])
 * @method static KissCompanyModel|null findOneBySocials($val, array $opt=[])
 * @method static KissCompanyModel|null findOneByAdditional($val, array $opt=[])
 *
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findByTstamp($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findByName($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findByLogo($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findByStreet($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findByPostal($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findByCity($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findByState($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findByCountry($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findByOpening_times($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findByClosing_times($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findByPhone_numbers($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findByEmails($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findByWebsites($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findByFax_numbers($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findBySocials($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findByAdditional($val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findMultipleByIds($var, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findBy($col, $val, array $opt=[])
 * @method static Collection|KissCompanyModel[]|KissCompanyModel|null findAll(array $opt=[])
 *
 * @method static integer countById($id, array $opt=[])
 * @method static integer countByTstamp($val, array $opt=[])
 * @method static integer countByName($val, array $opt=[])
 * @method static integer countByLogo($val, array $opt=[])
 * @method static integer countByStreet($val, array $opt=[])
 * @method static integer countByPostal($val, array $opt=[])
 * @method static integer countByCity($val, array $opt=[])
 * @method static integer countByState($val, array $opt=[])
 * @method static integer countByCountry($val, array $opt=[])
 * @method static integer countByOpening_times($val, array $opt=[])
 * @method static integer countByClosing_times($val, array $opt=[])
 * @method static integer countByPhone_numbers($val, array $opt=[])
 * @method static integer countByEmails($val, array $opt=[])
 * @method static integer countByWebsites($val, array $opt=[])
 * @method static integer countByFax_numbers($val, array $opt=[])
 * @method static integer countBySocials($val, array $opt=[])
 * @method static integer countByAdditional($val, array $opt=[])
 */
class KissCompanyModel extends Model
{
    protected static $strTable = 'tl_kiss_company';
}
