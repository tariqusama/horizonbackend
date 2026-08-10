<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->auditAction('Created');
        });

        static::updated(function ($model) {
            $model->auditAction('Updated');
        });

        static::deleted(function ($model) {
            $model->auditAction('Deleted');
        });
    }

    protected function formatAuditValue($value)
    {
        if (is_array($value)) {
            return json_encode($value);
        }

        if (is_object($value)) {
            return get_class($value);
        }

        return var_export($value, true);
    }

    protected function auditAction($actionType)
    {
        $target = class_basename(get_class($this)) . ' #' . $this->getKey();

        $details = [];
        if ($actionType === 'Updated') {
            $changes = $this->getDirty();
            foreach ($changes as $key => $value) {
                $original = $this->getOriginal($key);
                $formattedOriginal = $this->formatAuditValue($original);
                $formattedValue = $this->formatAuditValue($value);
                $details[] = "{$key}: {$formattedOriginal} -> {$formattedValue}";
            }
        } elseif ($actionType === 'Created') {
            $details[] = 'Attributes: ' . json_encode($this->getAttributes());
        }

        AuditLog::create([
            'user_id' => Auth::id(), // Nullable now
            'action' => $actionType . ' ' . class_basename(get_class($this)),
            'target' => $target,
            'details' => empty($details) ? null : implode(', ', $details),
            'ip_address' => request()->ip(),
        ]);
    }
}
