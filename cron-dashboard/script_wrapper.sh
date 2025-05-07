#!/bin/bash
# script_wrapper.sh - Wrapper for your script to log execution to the database

# Path to your Python script
ORIGINAL_SCRIPT="/path/to/rpscrape/scripts/racecards.py"

# The parameter to pass to your script
SCRIPT_PARAM="today"

# Path to the SQLite database
DB_PATH="/path/to/racingpuzzle.db"

# Get script name
SCRIPT_NAME=$(basename "$ORIGINAL_SCRIPT")

# Insert record with "running" status
RUN_ID=$(sqlite3 "$DB_PATH" "INSERT INTO job_runs (job_name, start_time, status) VALUES ('$SCRIPT_NAME', datetime('now'), 'running'); SELECT last_insert_rowid();")

# Run the original Python script and capture output and exit code
OUTPUT=$(python "$ORIGINAL_SCRIPT" "$SCRIPT_PARAM" 2>&1)
EXIT_CODE=$?

# Determine status based on exit code
if [ $EXIT_CODE -eq 0 ]; then
    STATUS="completed"
else
    STATUS="failed"
fi

# Escape single quotes in output for SQLite
OUTPUT_ESCAPED=$(echo "$OUTPUT" | sed "s/'/''/g")

# Update record with results
sqlite3 "$DB_PATH" "UPDATE job_runs SET end_time = datetime('now'), status = '$STATUS', output = '$OUTPUT_ESCAPED' WHERE id = $RUN_ID"

# If original script failed, exit with its exit code
if [ $EXIT_CODE -ne 0 ]; then
    exit $EXIT_CODE
fi