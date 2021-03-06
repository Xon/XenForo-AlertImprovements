<?php

class SV_AlertImprovements_XenForo_AlertHandler_User extends XFCP_SV_AlertImprovements_XenForo_AlertHandler_User implements SV_AlertImprovements_IConsolidateAlertHandler
{
    public function canSummarizeForUser(array $optOuts, array $viewingUser)
    {
        return true;
    }

    public function canSummarizeItem(array $alert)
    {
        switch ($alert['content_type'])
        {
            case 'profile_post':
            case 'profile_post_comment':
            case 'report_comment':
            case 'conversation_message':
            case 'post':
                return $alert['action'] == 'like';
            case 'postrating':
                return $alert['action'] == 'rate';
            default:
                return false;
        }
    }

    public function consolidateAlert(&$contentType, &$contentId, array $item)
    {
        return false;
    }

    public function summarizeAlerts(array $summaryAlert, array $alerts, $groupingStyle)
    {
        if ($groupingStyle != 'user')
        {
            return null;
        }
        $summaryAlert = SV_AlertImprovements_Helper::getSummaryAlertLikeRatings($summaryAlert, $alerts);
        $summaryAlert['action'] = isset($summaryAlert['extra_data']['ratings'])
            ? 'rate_summary'
            : 'like_summary';

        return $summaryAlert;
    }

    protected function _prepareRate_summary(array $item, array $viewingUser)
    {
        if (is_callable('parent::_prepareRate_summary'))
        {
            /** @noinspection PhpUndefinedMethodInspection */
            $item = parent::_prepareRate_summary($item, $viewingUser);
        }

        $item["isSummary"] = true;

        return SV_AlertImprovements_Helper::prepareRateSummary($item, $viewingUser);
    }

    protected function _prepareLike_summary(array $item, array $viewingUser)
    {
        if (is_callable('parent::_prepareLike_summary'))
        {
            /** @noinspection PhpUndefinedMethodInspection */
            $item = parent::_prepareLike_summary($item, $viewingUser);
        }

        $item["isSummary"] = true;

        return SV_AlertImprovements_Helper::prepareRateSummary($item, $viewingUser);
    }
}
