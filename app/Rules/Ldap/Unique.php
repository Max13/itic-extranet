<?php

namespace App\Rules\Ldap;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class Unique implements ValidationRule
{
    /**
     * The class name which will be unique
     *
     * @var string
     */
    protected $className;

    /**
     * GUID to ignore
     *
     * @var string
     */
    protected $ignoredGuid = null;

    /**
     * Construct the validator
     *
     * @param string $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * Ignore specific GUID from the search
     *
     * @param  string  $guid
     * @return $this
     */
    public function ignore($guid)
    {
        $this->ignoredGuid = $guid;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = call_user_func([$this->className, 'query']);

        $query->where($attribute, $value);
        if ($this->ignoredGuid) {
            $query->where($query->getModel()->getObjectGuidKey(), '!=', $this->ignoredGuid);
        }

        if ($query->exists()) {
            $fail('This :attribute must be unique');
        }
    }
}
