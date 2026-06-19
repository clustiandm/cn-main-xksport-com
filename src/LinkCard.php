<?php

namespace App\Utility;

/**
 * 链接卡片渲染工具
 */
class LinkCard
{
    /**
     * 默认配置
     */
    private array $config = [
        'url' => 'https://cn-main-xksport.com',
        'keyword' => '星空体育app',
        'width' => '300px',
        'borderColor' => '#1a73e8',
        'backgroundColor' => '#ffffff',
        'textColor' => '#333333',
        'showDomain' => true,
        'truncateLength' => 50,
    ];

    /**
     * 构造函数
     */
    public function __construct(array $options = [])
    {
        $this->config = array_merge($this->config, $options);
    }

    /**
     * 渲染链接卡片
     *
     * @param string|null $url    覆盖默认URL
     * @param string|null $keyword 覆盖默认关键词
     * @return string 经过转义的HTML片段
     */
    public function render(?string $url = null, ?string $keyword = null): string
    {
        $useUrl = $url ?? $this->config['url'];
        $useKeyword = $keyword ?? $this->config['keyword'];
        $useWidth = $this->config['width'];
        $useBorderColor = $this->config['borderColor'];
        $useBgColor = $this->config['backgroundColor'];
        $useTextColor = $this->config['textColor'];
        $useDomain = $this->config['showDomain'];
        $truncate = $this->config['truncateLength'];

        // 安全转义所有输出值
        $escapedUrl = htmlspecialchars($useUrl, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedKeyword = htmlspecialchars($useKeyword, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedWidth = htmlspecialchars($useWidth, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedBorderColor = htmlspecialchars($useBorderColor, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedBgColor = htmlspecialchars($useBgColor, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedTextColor = htmlspecialchars($useTextColor, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 截断关键词（如果需要）
        $displayKeyword = $escapedKeyword;
        if (mb_strlen($displayKeyword) > $truncate) {
            $displayKeyword = mb_substr($displayKeyword, 0, $truncate) . '...';
        }

        // 提取并转义域名
        $domain = '';
        if ($useDomain) {
            $parsed = parse_url($useUrl);
            $host = $parsed['host'] ?? $useUrl;
            $domain = htmlspecialchars($host, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        // 构建HTML结构
        $html = '<div class="link-card" style="width: ' . $escapedWidth . '; border: 1px solid ' . $escapedBorderColor . '; background-color: ' . $escapedBgColor . '; border-radius: 8px; padding: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">' . "\n";
        $html .= '    <div class="link-card-content" style="display: flex; flex-direction: column; gap: 8px;">' . "\n";
        $html .= '        <div class="link-card-title" style="color: ' . $escapedTextColor . '; font-size: 16px; font-weight: bold; word-wrap: break-word;">' . $displayKeyword . '</div>' . "\n";
        if ($domain !== '') {
            $html .= '        <div class="link-card-url" style="color: #666666; font-size: 12px; word-wrap: break-word;">' . $domain . '</div>' . "\n";
        }
        $html .= '        <a href="' . $escapedUrl . '" target="_blank" rel="noopener noreferrer" style="display: inline-block; margin-top: 8px; padding: 8px 16px; background-color: ' . $escapedBorderColor . '; color: #ffffff; text-decoration: none; border-radius: 4px; text-align: center; font-size: 14px;">' . "\n";
        $html .= '            Visit ' . $displayKeyword . "\n";
        $html .= '        </a>' . "\n";
        $html .= '    </div>' . "\n";
        $html .= '</div>';

        return $html;
    }

    /**
     * 渲染多个链接卡片
     *
     * @param array $links 每个元素为 ['url' => string, 'keyword' => string]
     * @return string
     */
    public function renderMultiple(array $links): string
    {
        $result = '';
        foreach ($links as $link) {
            $url = $link['url'] ?? $this->config['url'];
            $keyword = $link['keyword'] ?? $this->config['keyword'];
            $result .= $this->render($url, $keyword) . "\n";
        }
        return $result;
    }

    /**
     * 获取当前配置（用于调试）
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}

// 使用示例（可移除）
// $card = new LinkCard();
// echo $card->render();