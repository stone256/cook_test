<?php

namespace Stone256\CookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * cooking
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Stone256\CookBundle\Entity\cookingRepository")
 */
class cooking
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="fridge", type="text")
     */
    private $fridge;

    /**
     * @var string
     *
     * @ORM\Column(name="recipes", type="text")
     */
    private $recipes;

    /**
     * @var string
     *
     * @ORM\Column(name="food", type="text",  nullable=true)
     */
    private $food;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return cooking
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set fridge
     *
     * @param string $fridge
     * @return cooking
     */
    public function setFridge($fridge)
    {
        $this->fridge = $fridge;

        return $this;
    }

    /**
     * Get fridge
     *
     * @return string 
     */
    public function getFridge()
    {
        return $this->fridge;
    }

    /**
     * Set recipes
     *
     * @param string $recipes
     * @return cooking
     */
    public function setRecipes($recipes)
    {
        $this->recipes = $recipes;

        return $this;
    }

    /**
     * Get recipes
     *
     * @return string 
     */
    public function getRecipes()
    {
        return $this->recipes;
    }

    /**
     * Set food
     *
     * @param string $food
     * @return cooking
     */
    public function setFood($food)
    {
        $this->food = $food;

        return $this;
    }

    /**
     * Get food
     *
     * @return string 
     */
    public function getFood()
    {
        return $this->food;
    }
    
    
    /**
     * Now we tell doctrine that before we persist or update we call the updatedTimestamps() function.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        if($this->getDate() == null)
        {
            $this->setDate(new \DateTime(date('Y-m-d H:i:s')));
        }
    }    
    

    /**
     * Get web path to upload directory.
     * 
     * @return string
     *   Relative path.
     */
    protected function getUploadPath()
    {
        return 'uploads/fridge';
    }


    /**
     * Get absolute path to upload directory.
     * 
     * @return string
     *   Absolute path.
     */
    protected function getUploadAbsolutePath()
    {
        return __DIR__ . '/../../../../web/' . $this->getUploadPath();
    }
    
    
    /**
     * Get web path to a file.
     * 
     * @return null|string
     *   Relative path.
     */
    public function getFridgeWeb() {
        return NULL === $this->getFridge()
                ? NULL
                : $this->getUploadPath() . '/' . $this->getFridge();
    }
    
    /**
     * Get path on disk to a file.
     * 
     * @return null|string
     *   Absolute path.
     */
    public function getFridgeAbsolute() {
        return NULL === $this->getFridge()
                ? NULL
                : $this->getUploadAbsolutePath() . '/' . $this->getFridge();
    }
    
    /**
     * @Assert\File(maxSize="700000")
     */
    private $file;

    
    
    /**
     * Sets file.
     * 
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setFile(UploadedFile $file = null){
      $this->file = $file;
    }
    
    
    /**
     * Get file.
     * 
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * upload fridge file
     */
    public function upload() {
        // File property can be empty.
        if (NULL === $this->getFile()) {
            return;
        }
        
        $filename = $this->getFile()->getClientOriginalName();
        
        // Move the uploaded file to target directory using original name.
        $this->getFile()->move(
                $this->getUploadAbsolutePath(),
                $filename);
        
        // Set the fridge.
        $this->setFridge($filename);
        
        // Cleanup.
        $this->setFile();
    }
    
    
}
