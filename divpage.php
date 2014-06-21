<?php
class DivPage {
    private $sql; // sql to query
    private $cols; // the items to output
    private $puts; // the strings to output, the output order like that puts[0] cols[0] puts[1] cols[1] ...
    private $max; // how many items per page
    private $max2; // how many links on the page bar
    private $curpage; // current page
    private $totalpage;
    private $url;
    private $con; // a database connection object
    private $lin;
    private $mid;
    public function __construct($con,$sql,$url,$max,$max2,$puts,$cols) {
        $this->sql=$sql;
        $this->curpage=0;
        $this->max=$max;
        $this->max2=$max2;
        $this->puts=$puts;
        $this->cols=$cols;
        $this->url=$url;
        $this->con=$con;
        $res=mysql_query($this->sql,$this->con);
        $this->totalpage=(int)ceil(mysql_num_rows($res)/$this->max);
        unset($res);
        if (strpos($this->url,'?',0)) {
            $this->lin='&';
        }else {
            $this->lin='?';
        }
        $this->mid=(int)ceil($this->max2/2);
    }
    private function showpagebar() {
        $rfix=0;
        $lfix=0;
        $tmp=$this->curpage-$this->mid;
        # $lran=$tmp>=0?$tmp:0;
        if ($tmp<0) {
            $lran=0;
            $rfix=-$tmp;
        } else {
            $lran=$tmp;
        }
        $tmp=$this->curpage+$this->mid;
        # $rran=$tmp<$this->totalpage?$tmp:$this->totalpage-1;
        if ($tmp > $this->totalpage) {
            $rran = $this->totalpage-1;
            $lfix = $this->totalpage-1-$tmp;
        } else {
            $rran=$tmp;
        }
        $lran+=$lfix;
        $rran+=$rfix;
        unset($lfix);
        unset($rfix);
        unset($tmp);
        $lran=$lran>=0?$lran:0;
        $rran=$rran<$this->totalpage?$rran:$this->totalpage-1;
        $str="<div class='pagebar'><a href='".$this->url.$this->lin."pageid=0'>首 页</a>&nbsp";
        for($ii=$lran; $ii != $rran+1; ++$ii) { 
           $str=$str."<a href='".$this->url.$this->lin."pageid=".$ii."'>".($ii+1)."</a>&nbsp";
        }
        $str=$str."<a href='".$this->url.$this->lin."pageid=".($this->totalpage-1)."'>尾 页</a>"."&nbsp;跳到<input type='text' class=‘goto' name='".$this->url.$this->lin."pageid' size='3'>页&nbsp;&nbsp;共".$this->totalpage."页</div>";
        return $str;
    }

    private function DividNow($sidbarnum=2) {
        echo "<div class='perpage'>";
        $sql_1=$this->sql." limit ".($this->max*$this->curpage).", ".$this->max;
        $res=mysql_query($sql_1,$this->con);
        while ($row=mysql_fetch_array($res)) {
            $str="";
            for ($ii=0; $ii != count($this->puts) && $ii != count($this->cols); ++$ii){
                $str=$str.$this->puts[$ii].$row[($this->cols[$ii])];
            }
            while ($ii<count($this->puts)){
                $str=$str.$this->puts[$ii];
                ++$ii;
            }
            while ($ii<count($this->cols)) {
                $str=$str.$row[($this->cols[$ii])];
                ++$ii;
            }
            echo $str;
        }
        echo "</div>";
    }

    public function gotopage($ii,$pagebarnum=1) {
        if ($ii>=0 && $ii<$this->totalpage) {
            $this->curpage=$ii;
            $pagebar=$this->showpagebar();
            if ($pagebarnum==2) {
                echo $pagebar;
            }elseif($pagebarnum!=1) {
                echo "error of pagebar numbers to show.<br>";
                return false;
            }
            $this->DividNow();
            echo $pagebar;
            return true;
        } else {
            echo "the page you setted not exist<br >";
            return false;
        }
    }

    public function __destruct() {
        //mysql_close($this->con);
        unset($this->con);
        unset($this->sql);
        unset($this->cols);
        unset($this->puts);
        unset($this->max);
        unset($this->max2);
        unset($this->curpage);
        unset($this->url);
    }
}
?>
