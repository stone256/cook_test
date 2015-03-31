<?php

namespace Stone256\CookBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Stone256\CookBundle\Entity\cooking;
use Stone256\CookBundle\Entity\fridge;
use Stone256\CookBundle\Entity\recipes;

class cookingControllerTest extends WebTestCase
{
    
    public function testCompleteScenario()
    {
    
	//test recipe data receiver
	$json = '[{"name": "grilled cheese on toast","ingredients": [{ "item":"bread", "amount":"2", "unit":"slices"},{ "item":"cheese", "amount":"2", "unit":"slices"}]},{"name": "salad sandwich","ingredients": [{ "item":"bread", "amount":"2", "unit":"slices"},{ "item":"mixed salad", "amount":"200", "unit":"grams"}]}]';
	$recipes = new recipes($json);
	$this->assertTrue($recipes->isError() === false);
    
	$json = '[{"name": "","ingredients": [{ "item":"bread", "amount":"2", "unit":"slices"},{ "item":"cheese", "amount":"2", "unit":"slices"}]},{"name": "salad sandwich","ingredients": [{ "item":"bread", "amount":"2", "unit":"slices"},{ "item":"mixed salad", "amount":"200", "unit":"grams"}]}]';
	$recipes = new recipes($json);
	$this->assertTrue($recipes->isError() !== false);
   
	$json = '[{"name": "abc","ingredients": [{ "item":"bread", "amount":"0", "unit":"slices"},{ "item":"cheese", "amount":"2", "unit":"slices"}]},{"name": "salad sandwich","ingredients": [{ "item":"bread", "amount":"2", "unit":"slices"},{ "item":"mixed salad", "amount":"200", "unit":"grams"}]}]';
	$recipes = new recipes($json);
	$this->assertTrue($recipes->isError() !== false);
   
	$json = '[{"name": "abc","ingredients": [{ "item":"bread", "amount":"1", "unit":"ss-slices"},{ "item":"cheese", "amount":"2", "unit":"slices"}]},{"name": "salad sandwich","ingredients": [{ "item":"bread", "amount":"2", "unit":"slices"},{ "item":"mixed salad", "amount":"200", "unit":"grams"}]}]';
	$recipes = new recipes($json);
	$this->assertTrue($recipes->isError() !== false);
      
	$json = '"name": "abc","ingredients": [{ "item"  dasdasdad ';
	$recipes = new recipes($json);
	$this->assertTrue($recipes->isError() !== false);

	//..
	//..
	//test 135 ..

	
	  return;
      }

    
}
