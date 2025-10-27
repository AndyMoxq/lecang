<?php
namespace ThankSong\Lecang\Response;

class GetWaStorageFeeRespone extends BasicResponse{
    /**
     * 获取数据列表
     * @return array
     */
    public function getData(): array {
        return $this -> getBody()['data']['list'] ?? [];
    }

    /**
     * 获取总数
     * @return int
     */
    public function getTotal(): int {
        return $this -> getBody()['data']['total'] ?? 0;
    }

    /**
     * 获取当前页码
     * @return int
     */
    public function getCurrentPage(): int {
        return $this -> getBody()['data']['pageNum'] ?? 1;
    }

    /**
     * 获取每页数量
     * @return int
     */
    public function getPageSize(): int {
        return $this -> getBody()['data']['pageSize'] ?? 200;
    }

    /**
     * 是否还有更多数据
     * @return bool
     */
    public function hasMore(): bool {
        return $this -> getCurrentPage() < $this -> getTotal() / $this -> getPageSize();
    }

}