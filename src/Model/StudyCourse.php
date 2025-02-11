<?php
namespace App\Model;

use App\Service\Config;
use App\Service\Scrape;

class StudyCourse{
    private ?int $id = null;
    private ?string $tokName = null;
    private ?string $shortType = null;
    private ?string $shortKind = null;
    private ?string $specialisation = null;
    private ?string $major = null;

    public function getId(): ?int{
        return $this->id;
    }
    public function setId(?int $id): void{
        $this->id = $id;
    }

    public function getTokName(): ?string{
        return $this->tokName;
    }
    public function setTokName(?string $tokName): void{
        $this->tokName = $tokName;
    }

    public function getShortType(): ?string{
        return $this->shortType;
    }
    public function setShortType(?string $shortType): void{
        $this->shortType = $shortType;
    }

    public function getShortKind(): ?string{
        return $this->shortKind;
    }
    public function setShortKind(?string $shortKind): void{
        $this->shortKind = $shortKind;
    }

    public function getSpecialisation(): ?string{
        return $this->specialisation;
    }
    public function setSpecialisation(?string $specialisation): void{
        $this->specialisation = $specialisation;
    }

    public function getMajor(): ?string{
        return $this->major;
    }
    public function setMajor(?string $major): void{
        $this->major = $major;
    }

    public static function fromArray($array): StudyCourse{
        $studyCourse = new self();
        $studyCourse->fill($array);
        return $studyCourse;
    }

    public function fill($array): StudyCourse{
        if(isset($array['id']) && !$this->getId()){
            $this->setId($array['id']);
        }
        if(isset($array['tokName'])){
            $this->setTokName($array['tokName']);
        }
        if(isset($array['shortType'])){
            $this->setShortType($array['shortType']);
        }
        if(isset($array['shortKind'])){
            $this->setShortKind($array['shortKind']);
        }
        if(isset($array['specialisation'])){
            $this->setSpecialisation($array['specialisation']);
        }
        if(isset($array['major'])){
            $this->setMajor($array['major']);
        }
        return $this;
    }

    public static function findAll(): array{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM StudyCourse');
        $stmt->execute();
        $studyCoursesArray = $stmt->fetchAll();
        $studyCourses = [];
        foreach($studyCoursesArray as $studyCourseArray){
            $studyCourses[] = self::fromArray($studyCourseArray);
        }
        return $studyCourses;
    }

    public function findStudyCourse(?string $tokName, ?string $shortType, ?string $shortKind, ?string $specialisation, ?string $major): ?StudyCourse{
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM StudyCourse WHERE tokName = :tokName AND shortType = :shortType AND shortKind = :shortKind AND specialisation = :specialisation AND major = :major');
        $stmt->execute(['tokName' => $tokName, 'shortType' => $shortType, 'shortKind' => $shortKind, 'specialisation' => $specialisation, 'major' => $major]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return null; // No study course found
        }
        return self::fromArray($result);
    }

    public function findAllStudyCourseByName(?string $tokName): array {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM StudyCourse WHERE tokName = :tokName');
        $stmt->execute(['tokName' => $tokName]);
        $studyCoursesArray = $stmt->fetchAll();
        $studyCourses = [];
        foreach($studyCoursesArray as $studyCourseArray){
            $studyCourses[] = self::fromArray($studyCourseArray);
        }
        return $studyCourses;
    }

    public static function findById(int $id): ?StudyCourse
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM StudyCourse WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return null;
        }
        return self::fromArray($result);
    }

    public static function findStudyCourseByName(string $tokName): ?array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM StudyCourse WHERE tokName LIKE :tokName');
        $stmt->execute(['tokName' => '%' . $tokName . '%']);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) {
            return null; // No study course found
        }
        $studyCourses = [];
        foreach ($result as $studyCourseArray) {
            $studyCourses[] = self::fromArray($studyCourseArray);
        }
        return $studyCourses;
    }


    public static function getMajorByName($major): ?string
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT major FROM StudyCourse WHERE major = :major');
        $stmt->execute(['major' => $major]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return null; // No major found
        }
        return $result['major'];
    }
    public function save(){
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if(!$this->getId()){
            $stmt = $pdo->prepare('INSERT INTO StudyCourse (tokName, shortType, shortKind, specialisation, major) VALUES (:tokName, :shortType, :shortKind, :specialisation, :major)');
            $stmt->execute(['tokName' => $this->getTokName(), 'shortType' => $this->getShortType(), 'shortKind' => $this->getShortKind(), 'specialisation' => $this->getSpecialisation(), 'major' => $this->getMajor()]);
            $this->setId($pdo->lastInsertId());
        }
        else{
            $stmt = $pdo->prepare('UPDATE StudyCourse SET tokName = :tokName, shortType = :shortType, shortKind = :shortKind, specialisation = :specialisation, major = :major WHERE id = :id');
            $stmt->execute(['tokName' => $this->getTokName(), 'shortType' => $this->getShortType(), 'shortKind' => $this->getShortKind(), 'specialisation' => $this->getSpecialisation(), 'major' => $this->getMajor(), 'id' => $this->getId()]);
        }
    }

    public function toArray(): array{
        return [
            'item' => $this->getTokName(),
        ];
    }
}