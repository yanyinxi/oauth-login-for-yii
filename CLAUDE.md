# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

---

## 项目配置

**项目名称**: oauth-login-for-yii
**技术栈**: Yii 1.x CWidget 扩展（PHP）
**功能**: 新浪微博和 QQ 的 OAuth 2.0 登录组件

---

## CHK 自动启用配置

> **重要**：本项目已启用 Claude Harness Kit (CHK)。
> 当你发起的任务涉及"分析"、"实现"、"设计"、"审查"时，
> **必须**自动启动 CHK 的多 Agent 协作流程。

---

### 任务分类与 Agent 调度规则

#### 1. 分析类任务（必须并行）

触发词：分析、研究、调研、探索、搜索

**自动执行**：
```
🚀 Phase 1: 并行研究（3 个 Agent 同时启动）

Agent 1: explore (Haiku)
   任务：搜索相关代码和调用链
   输出：找到的相关文件列表

Agent 2: codebase-analyzer (Haiku)
   任务：分析模块结构和依赖关系
   输出：模块关系图

Agent 3: impact-analyzer (Haiku)
   任务：评估变更影响范围
   输出：影响文件清单 + 风险等级

汇总输出到 research/summary.md
```

#### 2. 设计类任务（Opus 模型）

触发词：设计、架构、技术方案、方案选择

**自动执行**：
```
🔧 Phase 2: 串行设计

Agent: architect (Opus)
   任务：基于 research/summary.md 设计架构
   输出：plan/architecture.md

Agent: tech-lead (Opus)
   任务：评审架构方案
   输出：plan/review.md
```

#### 3. 实现类任务（并行）

触发词：实现、完成、编写、写代码

**自动执行**：
```
🔨 Phase 3: 并行编码

Agent: backend-dev (Sonnet)
   任务：基于 plan/architecture.md 实现后端
   输出：output/task_backend.md

Agent: frontend-dev (Sonnet)
   任务：基于 plan/architecture.md 实现前端
   输出：output/task_frontend.md
```

#### 4. 审查类任务（并行）

触发词：审查、检查、评审、review

**自动执行**：
```
🔍 Phase 4: 并行审查

Agent: code-reviewer (Sonnet)
   任务：5 维度审查代码
   输出：review/report.md

Agent: qa-tester (Sonnet)
   任务：验证测试覆盖
   输出：test-report.md

Agent: security-auditor (Opus) — 如涉及安全
   任务：安全审计
   输出：security-report.md
```

---

### 触发示例

#### 示例 1：分析任务
```
你: 分析一下如何实现用户登录功能，使用这个 OAuth 扩展

CHK 自动执行:
  1. explore 搜索 OauthLogin.php, sinaWeibo.php, qqConnect.php
  2. codebase-analyzer 分析模块结构
  3. impact-analyzer 评估实现方案
  4. 汇总到 research/summary.md
  5. 基于分析结果给出实现建议
```

#### 示例 2：设计任务
```
你: 设计回调 Controller 的架构

CHK 自动执行:
  1. architect (Opus) 基于已有分析设计方案
  2. 输出架构设计到 plan/architecture.md
```

#### 示例 3：审查任务
```
你: 审查现有的 OAuth 实现

CHK 自动执行:
  1. code-reviewer 审查代码质量
  2. 5 维度评分：正确性/可读性/架构/安全/性能
  3. 输出 review/report.md
```

---

## 项目概述

This is a Yii 1.x CWidget extension that provides OAuth 2.0 login functionality for Sina Weibo and QQ (Tencent).

## Architecture

- `OauthLogin.php` — Main CWidget. Publishes assets, initializes both OAuth providers in `init()`, renders login buttons in `run()`.
- `qq/qqConnect.php` — QQ OAuth 2.0 client (`qqConnectAuthV2` class).
- `sina/sinaWeibo.php` — Sina Weibo OAuth 2.0 client (`SaeTOAuthV2` class), a large SDK-style file (~107KB).
- `views/` — Three view templates for login button sizes: `small_login`, `medium_login`, `big_login`.
- `config/main.php` — OAuth credentials (app keys, secrets, callback URLs) as PHP `define()` statements.
- `assets/` — CSS and button logo images.

## Adding as a Yii Extension

Place this extension under `protected/extensions/oauthLogin/` and configure in `main.php`:

```php
'import' => array(
    'ext.oauthLogin.OauthLogin',
),
```

## Usage

```php
$this->widget('oauthLogin', array(
    'itemView'  => 'small_login',  // or 'medium_login', 'big_login'
    'back_url'  => 'http://your-site.com/after-login',
));
```

## Required Yii App Params

The widget expects session keys: `sina_state`, `qq_state`, `back_url`. The calling application should handle the OAuth callback endpoints (`site/wblogin`, `site/qqlogin` in the demo) and validate the state + exchange codes for tokens using the respective OAuth client classes.

## Configuration Keys

| Provider | App Key | App Secret | Callback |
|----------|---------|------------|----------|
| Sina     | `WB_AKEY` | `WB_SKEY` | `WB_CALLBACK_URL` |
| QQ       | `QQ_APPID` | `QQ_APPKEY` | `QQ_CALLBACK_URL` |

---

## 已知限制

- 本项目是 Widget 扩展，不是完整应用
- 需要配合 Yii 应用才能完成完整的 OAuth 登录流程
- 回调 Controller 需要在调用方项目中实现

---

## CHK 状态查看

```bash
~/claude-harness-kit/cli/kit.sh status
```