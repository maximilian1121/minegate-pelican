<x-filament-panels::page>
    <div wire:poll.2s="pollStatus">
        @if ($status === null)
            <div class="fi-section rounded-xl p-6">
                <p class="text-danger-600 font-medium">
                    Could not reach Minegate at the configured API host.
                </p>
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-4">
                <div class="fi-section rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-500">Uptime</p>
                    <p class="text-lg font-semibold">{{ $status['uptime'] }}</p>
                </div>
                <div class="fi-section rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-500">Process RAM</p>
                    <p class="text-lg font-semibold">{{ $status['process']['ram_mb'] }} MB</p>
                </div>
                <div class="fi-section rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-500">System RAM</p>
                    <p class="text-lg font-semibold">{{ $status['system']['ram_percent'] }}%</p>
                    <p class="text-xs text-gray-500">
                        {{ $status['system']['ram_used_mb'] }} / {{ $status['system']['ram_total_mb'] }} MB
                    </p>
                </div>
                <div class="fi-section rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-500">System CPU</p>
                    <p class="text-lg font-semibold">{{ $status['system']['cpu_percent'] }}%</p>
                    <p class="text-xs text-gray-500">{{ $status['system']['cpu_count'] }} cores</p>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 mt-4">
                <div class="fi-section rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-500 mb-2">Throughput</p>
                    <p>Sent {{ $status['throughput']['bytes_sent_rate_human'] }}</p>
                    <p>Received {{ $status['throughput']['bytes_recv_rate_human'] }}</p>
                </div>
                <div class="fi-section rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-500 mb-2">Routes</p>
                    <p>{{ $status['network']['online_routes'] }}/{{ $status['network']['total_routes'] }} online</p>
                    <p>{{ $status['network']['total_connections'] }} active connections</p>
                    <p class="text-xs text-gray-500 mt-1">Root domain, {{ $status['network']['root_domain'] }}</p>
                </div>
            </div>

            <div class="fi-section rounded-xl p-4 mt-4">
                <p class="text-xs font-medium text-gray-500 mb-2">Live routes</p>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="pr-4">Subdomain</th>
                            <th class="pr-4">Host</th>
                            <th class="pr-4">Port</th>
                            <th class="pr-4">Online</th>
                            <th>Connections</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($status['routes'] as $route)
                            <tr>
                                <td class="pr-4">{{ $route['subdomain'] }}</td>
                                <td class="pr-4">{{ $route['host'] }}</td>
                                <td class="pr-4">{{ $route['port'] }}</td>
                                <td class="pr-4">{{ $route['online'] ? 'Yes' : 'No' }}</td>
                                <td>{{ $route['active_connections'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-filament-panels::page>