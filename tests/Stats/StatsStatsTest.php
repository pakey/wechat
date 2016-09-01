<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use EasyWeChat\Stats\Stats;

class StatsStatsTest extends TestCase
{
    public function getStats()
    {
        $stats = Mockery::mock('EasyWeChat\Stats\Stats[parseJSON]', [Mockery::mock('EasyWeChat\Core\AccessToken')]);
        $stats->shouldReceive('parseJSON')->andReturnUsing(function ($method, $params) {
            return [
                'api' => $params[0],
                'params' => empty($params[1]) ? null : $params[1],
            ];
        });

        return $stats;
    }

    /**
     * Test userSummary().
     */
    public function testuserSummary()
    {
        $stats = $this->getStats();

        $result = $stats->userSummary(1, 2);
        $this->assertEquals(Stats::API_USER_SUMMARY, $result['api']);
        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test userCumulate().
     */
    public function testuserCumulate()
    {
        $stats = $this->getStats();

        $result = $stats->userCumulate(1, 2);
        $this->assertEquals(Stats::API_USER_CUMULATE, $result['api']);
        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test articleSummary().
     */
    public function testarticleSummary()
    {
        $stats = $this->getStats();

        $result = $stats->articleSummary(1, 2);
        $this->assertEquals(Stats::API_ARTICLE_SUMMARY, $result['api']);
        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test articleTotal().
     */
    public function testarticleTotal()
    {
        $stats = $this->getStats();

        $result = $stats->articleTotal(1, 2);
        $this->assertEquals(Stats::API_ARTICLE_TOTAL, $result['api']);
        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test userReadSummary().
     */
    public function testuserReadSummary()
    {
        $stats = $this->getStats();

        $result = $stats->userReadSummary(1, 2);
        $this->assertEquals(Stats::API_USER_READ_SUMMARY, $result['api']);
        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test userReadHourly().
     */
    public function testuserReadHourly()
    {
        $stats = $this->getStats();

        $result = $stats->userReadHourly(1, 2);
        $this->assertEquals(Stats::API_USER_READ_HOURLY, $result['api']);
        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test userShareSummary().
     */
    public function testuserShareSummary()
    {
        $stats = $this->getStats();

        $result = $stats->userShareSummary(1, 2);
        $this->assertEquals(Stats::API_USER_SHARE_SUMMARY, $result['api']);
        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test userShareHourly().
     */
    public function testuserShareHourly()
    {
        $stats = $this->getStats();

        $result = $stats->userShareHourly(1, 2);
        $this->assertEquals(Stats::API_USER_SHARE_HOURLY, $result['api']);
        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test upstreamMessageSummary().
     */
    public function testupstreamMessageSummary()
    {
        $stats = $this->getStats();

        $result = $stats->upstreamMessageSummary(1, 2);
        $this->assertEquals(Stats::API_UPSTREAM_MSG_SUMMARY, $result['api']);
        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test upstreamMessageHourly().
     */
    public function testupstreamMessageHourly()
    {
        $stats = $this->getStats();

        $result = $stats->upstreamMessageHourly(1, 2);
        $this->assertEquals(Stats::API_UPSTREAM_MSG_HOURLY, $result['api']);
        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test upstreamMessageWeekly().
     */
    public function testupstreamMessageWeekly()
    {
        $stats = $this->getStats();

        $result = $stats->upstreamMessageWeekly(1, 2);
        $this->assertEquals(Stats::API_UPSTREAM_MSG_WEEKLY, $result['api']);
        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test upstreamMessageMonthly().
     */
    public function testupstreamMessageMonthly()
    {
        $stats = $this->getStats();

        $result = $stats->upstreamMessageMonthly(1, 2);
        $this->assertEquals(Stats::API_UPSTREAM_MSG_MONTHLY, $result['api']);
        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test upstreamMessageDistSummary().
     */
    public function testupstreamMessageDistSummary()
    {
        $stats = $this->getStats();

        $result = $stats->upstreamMessageDistSummary(1, 2);
        $this->assertEquals(Stats::API_UPSTREAM_MSG_DIST_SUMMARY, $result['api']);
        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test upstreamMessageDistWeekly().
     */
    public function testupstreamMessageDistWeekly()
    {
        $stats = $this->getStats();

        $result = $stats->upstreamMessageDistWeekly(1, 2);
        $this->assertEquals(Stats::API_UPSTREAM_MSG_DIST_WEEKLY, $result['api']);
        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test upstreamMessageDistMonthly().
     */
    public function testupstreamMessageDistMonthly()
    {
        $stats = $this->getStats();

        $result = $stats->upstreamMessageDistMonthly(1, 2);
        $this->assertEquals(Stats::API_UPSTREAM_MSG_DIST_MONTHLY, $result['api']);
        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test interfaceSummary().
     */
    public function testinterfaceSummary()
    {
        $stats = $this->getStats();

        $result = $stats->interfaceSummary(1, 2);
        $this->assertEquals(Stats::API_INTERFACE_SUMMARY, $result['api']);

        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    /**
     * Test interfaceSummaryHourly().
     */
    public function testinterfaceSummaryHourly()
    {
        $stats = $this->getStats();

        $result = $stats->interfaceSummaryHourly(1, 2);
        $this->assertEquals(Stats::API_INTERFACE_SUMMARY_HOURLY, $result['api']);

        $this->assertEquals(['begin_date' => 1, 'end_date' => 2], $result['params']);
    }

    //拉取卡券概况数据接口.
    public function testCardSummary()
    {
        $stats = $this->getStats();

        $beginDate = '2016-06-20';
        $endDate = '2016-06-28';
        $condSource = 1; //卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据

        $result = $stats->cardSummary($beginDate, $endDate, $condSource);
        $this->assertStringStartsWith(Stats::API_CARD_SUMMARY, $result['api']);
        $this->assertEquals($beginDate, $result['params']['begin_date']);
        $this->assertEquals($endDate, $result['params']['end_date']);
        $this->assertEquals($condSource, $result['params']['cond_source']);
    }

    //获取免费券数据接口.
    public function testFreeCardSummary()
    {
        $stats = $this->getStats();

        $beginDate = '2016-06-20';
        $endDate = '2016-06-28';
        $condSource = 1; //卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据
        $cardId = '';

        $result = $stats->freeCardSummary($beginDate, $endDate, $condSource, $cardId);
        $this->assertStringStartsWith(Stats::API_FREE_CARD_SUMMARY, $result['api']);
        $this->assertEquals($beginDate, $result['params']['begin_date']);
        $this->assertEquals($endDate, $result['params']['end_date']);
        $this->assertEquals($condSource, $result['params']['cond_source']);
        $this->assertEquals($cardId, $result['params']['card_id']);
    }

    //拉取会员卡数据接口.
    public function testMemberCardSummary()
    {
        $stats = $this->getStats();

        $beginDate = '2015-05-20';
        $endDate = '2015-07-20';
        $condSource = 1; //卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据

        $result = $stats->memberCardSummary($beginDate, $endDate, $condSource);
        $this->assertStringStartsWith(Stats::API_MEMBER_CARD_SUMMARY, $result['api']);
        $this->assertEquals($beginDate, $result['params']['begin_date']);
        $this->assertEquals($endDate, $result['params']['end_date']);
        $this->assertEquals($condSource, $result['params']['cond_source']);
    }
}
