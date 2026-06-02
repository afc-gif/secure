<x-dashboard.shell title="Contractual Vault" eyebrow="Member Dashboard">
    @php
        $panel = $individualPanel;
    @endphp

    <style>
        .member-vault {
            background: #000;
            color: #f5f5f5;
            font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .member-vault .vault-section + .vault-section {
            margin-top: clamp(4rem, 8vw, 6.5rem);
        }

        .member-vault .vault-heading {
            display: flex;
            align-items: flex-start;
            gap: 0.9rem;
            font-size: clamp(2rem, 5vw, 3.75rem);
            font-weight: 500;
            line-height: 1.15;
            letter-spacing: 0;
        }

        .member-vault .vault-icon {
            width: 1.15em;
            flex: 0 0 auto;
            line-height: 1.05;
        }

        .member-vault ul {
            list-style-type: circle;
        }

        .member-vault .vault-list {
            margin-top: clamp(2.25rem, 5vw, 4rem);
            padding-left: clamp(1.25rem, 4vw, 3.25rem);
        }

        .member-vault .vault-list > li {
            padding-left: clamp(1.4rem, 3vw, 2.8rem);
            margin-top: clamp(2rem, 4.5vw, 3.5rem);
            font-size: clamp(1.6rem, 4vw, 3rem);
            line-height: 1.45;
        }

        .member-vault .vault-nested {
            margin-top: 1.25rem;
            padding-left: clamp(2.25rem, 8vw, 6.5rem);
        }

        .member-vault .vault-nested > li {
            padding-left: clamp(1rem, 3vw, 2.75rem);
            margin-top: clamp(1.35rem, 3.5vw, 2.5rem);
            font-size: clamp(1.45rem, 3.6vw, 2.75rem);
            line-height: 1.45;
        }

        .member-vault strong {
            font-weight: 800;
        }

        .member-vault .vault-code {
            display: inline;
            box-decoration-break: clone;
            -webkit-box-decoration-break: clone;
            border-radius: 0.55em;
            background: rgba(45, 45, 45, 0.72);
            color: #9b9b9b;
            padding: 0.05em 0.35em 0.12em;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
            font-size: 0.85em;
            line-height: 1.4;
            word-break: break-word;
        }

        .member-vault .vault-arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.2em;
            height: 1.2em;
            margin-right: 0.25em;
            border-radius: 0.22em;
            background: linear-gradient(180deg, #dff3ff, #5d91ba 70%, #416f9b);
            color: #fff;
            font-size: 0.8em;
            font-weight: 900;
            vertical-align: 0.05em;
        }

        .member-vault .milestone-track {
            position: relative;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        .member-vault .milestone-track::before {
            position: absolute;
            top: 1.1rem;
            left: 8%;
            right: 8%;
            height: 0.28rem;
            content: "";
            background: linear-gradient(90deg, #f5f5f5 0%, #9b9b9b 50%, #323232 100%);
        }

        .member-vault .milestone-node {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 0.65rem;
            justify-items: center;
            text-align: center;
            font-size: clamp(0.95rem, 2.4vw, 1.35rem);
            line-height: 1.3;
            color: #bdbdbd;
        }

        .member-vault .milestone-node::before {
            width: 2.35rem;
            height: 2.35rem;
            border: 0.32rem solid #f5f5f5;
            border-radius: 999px;
            content: "";
            background: #000;
        }

        .member-vault .milestone-node.is-active::before {
            background: #f5f5f5;
            box-shadow: 0 0 0 0.4rem rgba(255, 255, 255, 0.14);
        }

        @media (max-width: 640px) {
            .member-vault {
                margin-left: -1.25rem;
                margin-right: -1.25rem;
                padding-left: 1.25rem;
                padding-right: 1.25rem;
            }

            .member-vault .milestone-track {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }

            .member-vault .milestone-track::before {
                top: 1rem;
                bottom: 1rem;
                left: 1rem;
                right: auto;
                width: 0.2rem;
                height: auto;
                background: linear-gradient(180deg, #f5f5f5 0%, #9b9b9b 50%, #323232 100%);
            }

            .member-vault .milestone-node {
                grid-template-columns: 2rem 1fr;
                justify-items: start;
                text-align: left;
            }

            .member-vault .milestone-node::before {
                width: 2rem;
                height: 2rem;
            }
        }
    </style>

    <article class="member-vault min-h-screen px-6 py-8 sm:px-10 lg:px-16 lg:py-12">
        <section class="vault-section">
            <h2 class="vault-heading"><span class="vault-icon">🔒</span><span>Step 1: Secure Gate Check (The Landing Entry)</span></h2>

            <ul class="vault-list">
                <li><strong>System Access Variable:</strong> <span class="vault-code">{{ $panel['gate']['variable'] }}</span></li>
                <li>
                    <strong>Hardcoded Access Input:</strong>
                    <span class="vault-code">{{ $panel['gate']['access_input'] }}</span>
                    (The explicit membership credential from image_39.png required to unlock her dashboard privilege).
                </li>
            </ul>
        </section>

        <section class="vault-section">
            <h2 class="vault-heading"><span class="vault-icon">💳</span><span>Step 2: The Core Asset Surface Post (The Headline Card)</span></h2>

            <ul class="vault-list">
                <li><strong>Primary Data Card Value:</strong> <span class="vault-code">{{ $panel['core']['value'] }}</span></li>
                <li><strong>Verification Tag:</strong> <span class="vault-code">{{ $panel['core']['verification'] }}</span></li>
            </ul>
        </section>

        <section class="vault-section">
            <h2 class="vault-heading"><span class="vault-icon">📍</span><span>Step 3: Information Source Coordinates &amp; Verified Payout Destination</span></h2>

            <ul class="vault-list">
                @foreach ($panel['dataBlocks'] as $block)
                    <li>
                        <strong>{{ $block['label'] }}:</strong>
                        <ul class="vault-nested">
                            <li>Header: <span class="vault-code">{{ $block['header'] }}</span></li>
                            <li>Assigned Ledger Allocation: <span class="vault-code">{{ $block['allocation'] }}</span></li>
                        </ul>
                    </li>
                @endforeach

                <li>
                    <strong>Secure Disbursement Coordinates (Read-Only Profile Box):</strong>
                    <ul class="vault-nested">
                        <li>Registered Recipient: <span class="vault-code">{{ $panel['disbursement']['recipient'] }}</span></li>
                        <li>Physical Footprint Address: <span class="vault-code">{{ $panel['disbursement']['address'] }}</span></li>
                        <li>Settlement Destination: <span class="vault-code">{{ $panel['disbursement']['destination'] }}</span></li>
                    </ul>
                </li>
            </ul>
        </section>

        <section class="vault-section">
            <h2 class="vault-heading"><span class="vault-icon">🗓️</span><span>Step 4: Synchronized Operational Timeline (The Progress Roadmap)</span></h2>

            <ul class="vault-list">
                <li>
                    <strong>Visual Element:</strong> Horizontal milestone tracking bar.
                    <div class="milestone-track" aria-label="Horizontal milestone tracking bar">
                        @foreach ($panel['milestones'] as $index => $milestone)
                            <div @class(['milestone-node', 'is-active' => $index <= 1])>
                                <span><strong>{{ $milestone['date'] }}:</strong> {{ $milestone['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </li>
                <li><strong>Active Parameter:</strong> <span class="vault-code">Batch 3 Synchronization Cycle</span></li>
                <li>
                    <strong>Hardcoded Milestones Displayed:</strong>
                    <ul class="vault-nested">
                        @foreach ($panel['milestones'] as $milestone)
                            <li><strong>{{ $milestone['date'] }}:</strong> <span class="vault-code">{{ $milestone['label'] }}</span></li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </section>

        <section class="vault-section">
            <h2 class="vault-heading"><span class="vault-icon">📄</span><span>Step 5: The Contractual Vault (The Historical Footprint)</span></h2>

            <ul class="vault-list">
                <li><strong>Document Line 1:</strong> <span class="vault-code">{{ $panel['documents']['line_1'] }}</span></li>
                <li><strong>Document Line 2:</strong> <span class="vault-code">{{ $panel['documents']['line_2'] }}</span></li>
                <li>
                    <strong>Historical Data Log:</strong>
                    <ul class="vault-nested">
                        @foreach ($panel['history'] as $history)
                            <li>
                                {{ $history['record'] }}: <span class="vault-code">{{ $history['date'] }}</span><br>
                                <span class="vault-arrow">➜</span><span class="vault-code">{{ $history['description'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </section>
    </article>
</x-dashboard.shell>
