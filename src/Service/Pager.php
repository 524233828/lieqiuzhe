<?php

namespace Service;

class Pager {

    protected $page;

    protected $size;

    protected $total_page;

    public function __construct($page = 1, $size = 20)
    {
        $this->page = abs(intval($page)) ?: 1;
        $this->size = abs(intval($size)) ?: 20;
    }

    public function getPager($total_count)
    {
        $this->total_page = ceil( $total_count / $this->size);

        if($this->page == $this->total_page){
            $count =  $total_count % $this->size;
        }else if($this->page > $this->total_page){
            $count = 0;
        }else{
            $count = $this->size;
        }

        return [
            'total'	=>	$total_count,
            'count'	=>	$count,
            'per_page'		=>	$this->size,
            'current_page'	=>	$this->page,
            'total_pages'	=>	$this->total_page,
            'prev_page'		=>	((1 == $this->page) ? 1 : ($this->page - 1)),
            'next_page'		=>	(($this->page == $this->total_page ) ? $this->total_page : ($this->page + 1)),
        ];
    }
}
