<?php

namespace App\Http\Controllers;

use App\Models\ActionLog;
use App\Services\ActionLogsService;

/**
 * 记录请求日志
 * @package App\Http\Controllers
 */
class ActionLogsController extends BaseController
{
    protected $actionLogsService;

    /**
     * ActionLogsController constructor.
     * @param $actionLogsService
     */
    public function __construct(ActionLogsService $actionLogsService)
    {
        $this->actionLogsService = $actionLogsService;
    }

    /**
     * 日志列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $actions = $this->actionLogsService->getActionLogs();

        return $this->view(null,compact('actions'));
    }

    /**
     * 删除日志
     * @param ActionLog $actionLog
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(ActionLog $actionLog)
    {
        $actionLog->delete();

        flash('删除日志成功')->success()->important();

        return redirect()->route('actions.index');
    }
}
