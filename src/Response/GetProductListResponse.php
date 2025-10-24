<?php
namespace ThankSong\Lecang\Response;

class GetProductListResponse extends BasicResponse
{
    /**
     * 获取总数量
     * @return int
     */
    public function getTotal(): int{
        return $this -> getBody()['data']['total'] ?? 0;
    }

    /**
     * 获取总页数
     * @return int
     */
    public function getPageSize(): int{
        return $this -> getBody()['data']['pageSize'] ?? 200;
    }

    /**
     * 获取当前页码
     * @return int
     */
    public function getCurrentPage(): int{
        return $this -> getBody()['data']['pageNum'] ?? 0;
    }

    /**
     * 获取数据
     * @return array
     */
    public function getData(): array{
        return $this -> getBody()['data']['list'] ?? [];
    }

    /**
     * 是否有更多数据
     * @return bool
     */
    public function hasMore(): bool{
        return $this -> getCurrentPage() < ($this -> getTotal() / $this -> getPageSize());
    }

    /**
     * 获取总页数
     * @return float
     */
    public function getTotalPage(): int{
        return ceil($this -> getTotal() / $this -> getPageSize());
    }


}