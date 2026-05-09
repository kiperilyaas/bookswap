#!/bin/bash
export ANTHROPIC_BASE_URL="http://localhost:20128/v1"
export ANTHROPIC_AUTH_TOKEN=""
export ANTHROPIC_API_KEY=""
export ANTHROPIC_MODEL="kr/claude-sonnet-4.5"
export ANTHROPIC_SMALL_FAST_MODEL="kr/claude-sonnet-4.5"
export CLAUDE_CODE_DISABLE_NONESSENTIAL_TRAFFIC=1

claude "$@"
