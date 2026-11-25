@extends('layouts.app')

@section('title', 'Customers')

@push('styles')
    <style>
        .customer-search {
            position: relative;
        }

        .customer-suggestions {
            position: absolute;
            z-index: 20;
            top: calc(100% + 0.375rem);
            right: 0;
            left: 0;
            max-height: 16rem;
            overflow-y: auto;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background: #fff;
            box-shadow: 0 8px 20px rgb(15 23 42 / 12%);
        }

        .customer-suggestions[hidden] {
            display: none;
        }

        .customer-suggestion {
            display: block;
            width: 100%;
            padding: 0.625rem 0.75rem;
            border: 0;
            background: transparent;
            color: #1f2937;
            font: inherit;
            text-align: left;
            cursor: pointer;
        }

        .customer-suggestion + .customer-suggestion {
            border-top: 1px solid #f3f4f6;
        }

        .customer-suggestion:hover,
        .customer-suggestion[aria-selected="true"] {
            background: #eff6ff;
            color: #1d4ed8;
        }

        @media (prefers-color-scheme: dark) {
            .customer-suggestions {
                border-color: #374151;
                background: #1f2937;
            }

            .customer-suggestion {
                color: #f9fafb;
            }

            .customer-suggestion + .customer-suggestion {
                border-color: #374151;
            }

            .customer-suggestion:hover,
            .customer-suggestion[aria-selected="true"] {
                background: #1e3a8a;
                color: #dbeafe;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-stack">
        <div class="page-header">
            <div>
                <h1>Customers</h1>
                <p class="empty-state">Manage your customer records.</p>
            </div>
            <a class="button button-primary" href="{{ route('customers.create') }}">Add Customer</a>
        </div>

        @if (session()->has('success'))
            <p class="notice-success" role="status">{{ session('success') }}</p>
        @endif
        @if (session()->has('error'))
            <p class="field-error" role="alert">{{ session('error') }}</p>
        @endif

        <section class="surface" aria-label="Customer list">
            <div class="surface-body">
                <form class="search-form" action="{{ route('customers.index') }}" method="GET">
                    <div class="form-field">
                        <label for="search">Search by name or company</label>
                        <div class="customer-search" data-customer-search
                            data-suggestions="{{ $customerSuggestions->toJson() }}">
                            <input class="form-control" id="search" name="search" type="search" value="{{ $search }}"
                                placeholder="Enter a name or company" autocomplete="off" role="combobox"
                                aria-autocomplete="list" aria-expanded="false" aria-controls="customer-suggestions">
                            <div class="customer-suggestions" id="customer-suggestions" role="listbox" hidden></div>
                        </div>
                    </div>
                    <button class="button button-primary" type="submit">Search</button>
                    @if ($search !== '')
                        <a class="button button-secondary" href="{{ route('customers.index') }}">Clear</a>
                    @endif
                </form>
            </div>

            @if ($customers->isEmpty())
                <p class="surface-body empty-state">
                    @if ($search !== '')
                        No customers match {{ $search }}.
                    @else
                        No customers have been added yet.
                    @endif
                </p>
            @else
                <div class="table-scroll">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->company ?? 'N/A' }}</td>
                                    <td>{{ $customer->created_at?->format('M j, Y') }}</td>
                                    <td>
                                        <div class="form-actions">
                                            <a class="button button-secondary" href="{{ route('customers.edit', $customer) }}">Edit</a>
                                            <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                                                data-customer-delete-form data-customer-name="{{ $customer->name }}"
                                                data-outstanding-orders="{{ $customer->outstanding_orders_count }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="button button-danger" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>

    <script>
        document.querySelectorAll('[data-customer-delete-form]').forEach((deleteForm) => {
            deleteForm.addEventListener('submit', (event) => {
                const outstandingOrders = Number(deleteForm.dataset.outstandingOrders);

                if (outstandingOrders > 0) {
                    event.preventDefault();
                    window.alert(`${deleteForm.dataset.customerName} has outstanding orders and cannot be deleted.`);

                    return;
                }

                if (!window.confirm('Are you sure you want to delete this customer?')) {
                    event.preventDefault();
                }
            });
        });

        const customerSearch = document.querySelector('[data-customer-search]');

        if (customerSearch) {
            const customerInput = customerSearch.querySelector('input');
            const suggestionsList = customerSearch.querySelector('[role="listbox"]');
            const customerNames = JSON.parse(customerSearch.dataset.suggestions);
            let activeSuggestionIndex = -1;

            const closeSuggestions = () => {
                suggestionsList.hidden = true;
                customerInput.setAttribute('aria-expanded', 'false');
                customerInput.removeAttribute('aria-activedescendant');
                activeSuggestionIndex = -1;
            };

            const setActiveSuggestion = (index) => {
                const suggestions = [...suggestionsList.querySelectorAll('[role="option"]')];

                if (suggestions.length === 0) {
                    return;
                }

                activeSuggestionIndex = (index + suggestions.length) % suggestions.length;
                suggestions.forEach((suggestion, suggestionIndex) => {
                    const isActive = suggestionIndex === activeSuggestionIndex;
                    suggestion.setAttribute('aria-selected', String(isActive));

                    if (isActive) {
                        customerInput.setAttribute('aria-activedescendant', suggestion.id);
                        suggestion.scrollIntoView({ block: 'nearest' });
                    }
                });
            };

            const renderSuggestions = () => {
                const searchTerm = customerInput.value.trim().toLocaleLowerCase();
                suggestionsList.replaceChildren();
                activeSuggestionIndex = -1;

                if (searchTerm.length === 0) {
                    closeSuggestions();
                    return;
                }

                const matches = customerNames
                    .filter((customerName) => customerName.toLocaleLowerCase().includes(searchTerm))
                    .slice(0, 8);

                if (matches.length === 0) {
                    closeSuggestions();
                    return;
                }

                matches.forEach((match, index) => {
                    const suggestion = document.createElement('button');
                    suggestion.type = 'button';
                    suggestion.className = 'customer-suggestion';
                    suggestion.id = `customer-suggestion-${index}`;
                    suggestion.setAttribute('role', 'option');
                    suggestion.setAttribute('aria-selected', 'false');
                    suggestion.textContent = match;
                    suggestion.addEventListener('click', () => {
                        customerInput.value = match;
                        closeSuggestions();
                        customerInput.focus();
                    });
                    suggestionsList.append(suggestion);
                });

                suggestionsList.hidden = false;
                customerInput.setAttribute('aria-expanded', 'true');
            };

            customerInput.addEventListener('input', renderSuggestions);
            customerInput.addEventListener('keydown', (event) => {
                if (suggestionsList.hidden) {
                    return;
                }

                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    setActiveSuggestion(activeSuggestionIndex + 1);
                } else if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    const suggestionCount = suggestionsList.querySelectorAll('[role="option"]').length;
                    setActiveSuggestion(activeSuggestionIndex < 0 ? suggestionCount - 1 : activeSuggestionIndex - 1);
                } else if (event.key === 'Enter' && activeSuggestionIndex >= 0) {
                    event.preventDefault();
                    customerInput.value = suggestionsList.querySelectorAll('[role="option"]')[activeSuggestionIndex].textContent;
                    closeSuggestions();
                } else if (event.key === 'Escape') {
                    closeSuggestions();
                }
            });

            document.addEventListener('click', (event) => {
                if (!customerSearch.contains(event.target)) {
                    closeSuggestions();
                }
            });
        }
    </script>
@endsection
