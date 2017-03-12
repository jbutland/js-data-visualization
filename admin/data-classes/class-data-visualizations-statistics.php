<?php
class Statistical_Functions {

  private $data_array;
  private $count;
  private $count_values;

  public function __construct()
  {

  }

  public function get_stats($data_array)
  {
    $this->data_array = $data_array;
    natsort($this->data_array);
    $this->data_array = array_values($this->data_array);
    $this->count = count($data_array);
    $this->count_values = array_count_values($data_array);
    uksort($this->count_values, "strnatcmp");
    //echo array_sum($this->data_array);
    print_r("mean: ".$this->mean()."<br>");
    print_r("median: ".$this->median()."<br>");
    print_r("mode: ".$this->mode()."<br>");
    print_r("Standard Deviation ".$this->standard_deviation()."<br>");
    print_r("First Quartile ".$this->quartiles(0.25)."<br>");
    print_r("Second Quartile ".$this->quartiles(0.50)."<br>");
    print_r("Third Quartile ".$this->quartiles(0.75)."<br>");
    print_r("Fourth Quartile ".$this->quartiles(1)."<br>");
    print_r($this->data_array);
    echo "<br>";
    print_r($this->count_values);
    echo "<br>";
    print_r("<br>");
    print_r($this->count_array);
  }

  private function mean()
  {
    if(is_numeric($this->data_array[0]))
    {
      $sum = array_sum($this->data_array);
      $mean = $sum / $this->count;
    }
    else
    {
      $count = count($this->count_values);
      foreach($this->count_values as $value)
      {
        $numerator += ($value * $count);
        $denominator += $value;
        $count--;
      }
      $mean = $numerator / $denominator;
    }
    return $mean;
  }

  public function median()
  {
    $middleval = floor( ($this->count -1 )/ 2); // find the middle value, or the lowest middle value
    if($count % 2) { // odd number, middle is the median
        $median = $this->data_array[$middleval];
    } else
    { // even number, calculate avg of 2 medians
      $low = $this->data_array[$middleval];
      $high = $this->data_array[$middleval+1];
      if($low === $high)
      {
        $median = $low;
      }
      else
      {
        $median = $low.",".$high;
      }

    }
    return $median;
  }

  private function mode()
  {
    $values = array_count_values($this->data_array);
    $mode = array_search(max($values), $values);
    return $mode;
  }

  private function standard_deviation()
  {
    if( count($this->data_array) < 2 ) {
      return;
    }

    $avg = $this->mean;

    $sum = 0;
    foreach($this->count_values as $value) {
      $sum += pow($value - $avg, 2);
    }

    return sqrt((1 / (count($this->count_values) - 1)) * $sum);
    }

  public function quartiles($quartile)
  {
    $pos = (count($this->data_array) - 1) * $quartile;

    $base = floor($pos);
    $rest = $pos - $base;
    if( isset($this->data_array[$base+1]) && is_numeric($this->data_array[$base+1])) {
      return $this->data_array[$base] + $rest * ($this->data_array[$base+1] - $this->data_array[$base]);
    }
    else
    {
      return $this->data_array[$base];
    }

  }

}
$data_array = array(0 =>'Bass',
                  1 => 'Vocals',
                  2 => 'Guitar',
                  3 => 'Guitar',
                  4 => 'Keyboard',
                  5 => 'Guitar',
                  6 => 'Bass',
                  7 => 'Drums',
                  8 => 'DJ',
                  9 => 'Guitar'
                );

$survey = new Statistical_Functions;

$survey->get_stats($data_array);
?>
