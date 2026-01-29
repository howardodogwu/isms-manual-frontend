<?php

return [
    // --- CLAUSE 4: CONTEXT ---
    'clause_4' => [
        'title' => 'Clause 4: Context of Organization',
        'intro' => 'Define who you are, where you work, and what data you protect.',
        
        'explanation' => "
            <h4 class='font-bold text-purple-900'>What is this?</h4>
            <p class='text-sm mt-2'>ISO 27001 requires you to define the 'boundaries' of your security. If you claim to be secure, does that apply to just your HQ, or your remote workers too?</p>
            <h4 class='font-bold text-purple-900 mt-4'>Industry Tip</h4>
            <p class='text-sm mt-2'>For <strong>SaaS/Tech</strong>: Mention your Cloud Provider (AWS/Azure) as part of your infrastructure scope.</p>
        ",

        'questions' => [
            [
                'key' => 'company_name',
                'label' => 'Legal Company Name',
                'type' => 'text',
                'placeholder' => 'e.g. PurpleWasp Ltd.',
                'required' => true,
                'min_length' => 2
            ],
            [
                'key' => 'c4_industry',
                'label' => 'Industry / Sector',
                'type' => 'text',
                'placeholder' => 'e.g. Fintech, HealthTech, E-commerce',
                'required' => true
            ],
            [
                'key' => 'c4_locations',
                'label' => 'Physical & Digital Scope',
                'type' => 'textarea',
                'placeholder' => 'e.g. London Headquarters (Physical) and AWS eu-west-1 Region (Cloud Infrastructure).',
                'required' => true,
                'min_length' => 15
            ],
            [
                'key' => 'c4_interested_parties',
                'label' => 'Key Interested Parties',
                'type' => 'textarea',
                'placeholder' => 'e.g. Clients, Investors, Regulatory Bodies (GDPR/ICO), Employees.',
                'required' => true
            ]
        ],
        'template' => "
            <h3>4. Context of the Organization</h3>
            <h4>4.1 Understanding the Organization</h4>
            <p>The Information Security Management System (ISMS) is established for <strong>{{company_name}}</strong>. The organization operates within the <strong>{{c4_industry}}</strong> sector and tailors its security controls to meet the specific regulatory and technical demands of this industry.</p>
            
            <h4>4.2 Interested Parties</h4>
            <p>The organization has determined that the following parties are relevant to the ISMS: <strong>{{c4_interested_parties}}</strong>.</p>
            
            <h4>4.3 Scope of the ISMS</h4>
            <p>The scope of this ISMS includes all information assets, processes, and technologies located at: <strong>{{c4_locations}}</strong>.</p>
        "
    ],

    // --- CLAUSE 5: LEADERSHIP ---
    'clause_5' => [
        'title' => 'Clause 5: Leadership',
        'intro' => 'Designate who is ultimately responsible for security failures.',
        'explanation' => "
            <h4 class='font-bold text-purple-900'>Why this matters</h4>
            <p class='text-sm mt-2'>An auditor will look for evidence that management actually cares. You cannot delegate 'Accountability'.</p>
            <p class='text-sm mt-2 text-blue-700'>The 'Approver' should usually be a C-Level executive.</p>
        ",
        'questions' => [
            [
                'key' => 'c5_approver_role',
                'label' => 'Job Title of Top Approver',
                'type' => 'text',
                'placeholder' => 'e.g. CEO or CTO',
                'required' => true
            ],
            [
                'key' => 'c5_ciso_name',
                'label' => 'Name of Security Lead (CISO)',
                'type' => 'text',
                'placeholder' => 'e.g. Jane Doe',
                'required' => true
            ]
        ],
        'template' => "
            <h3>5. Leadership</h3>
            <h4>5.1 Leadership and Commitment</h4>
            <p>Top management, represented by the <strong>{{c5_approver_role}}</strong>, demonstrates leadership and commitment with respect to the ISMS by ensuring the information security policy and objectives are established.</p>
            
            <h4>5.3 Organizational Roles</h4>
            <p>Top management has assigned the responsibility and authority for ensuring the ISMS conforms to ISO 27001 requirements to <strong>{{c5_ciso_name}}</strong>.</p>
        "
    ],

    // --- CLAUSE 6: PLANNING ---
    'clause_6' => [
        'title' => 'Clause 6: Planning & Risk',
        'intro' => 'How do you identify and handle security risks?',
        'explanation' => "
            <h4 class='font-bold text-purple-900'>Risk Assessment</h4>
            <p class='text-sm mt-2'>You must define <em>how</em> you calculate risk (Methodology). Common standards are ISO 27005 or NIST SP 800-30.</p>
        ",
        'questions' => [
            [
                'key' => 'c6_risk_methodology',
                'label' => 'Risk Methodology Used',
                'type' => 'text',
                'placeholder' => 'e.g. ISO 27005 or NIST SP 800-30',
                'required' => true
            ],
            [
                'key' => 'c6_review_frequency',
                'label' => 'Risk Review Frequency',
                'type' => 'text',
                'placeholder' => 'e.g. Quarterly or Annually',
                'required' => true
            ]
        ],
        'template' => "
            <h3>6. Planning</h3>
            <h4>6.1 Actions to address risks and opportunities</h4>
            <p>The organization applies a risk assessment process based on <strong>{{c6_risk_methodology}}</strong>.</p>
            <p>Information security risk assessments are conducted at planned intervals, specifically <strong>{{c6_review_frequency}}</strong>, or when significant changes occur.</p>
        "
    ],

    // --- CLAUSE 7: SUPPORT ---
    'clause_7' => [
        'title' => 'Clause 7: Support',
        'intro' => 'Do your employees have the resources and training they need?',
        'explanation' => "
            <h4 class='font-bold text-purple-900'>Competence</h4>
            <p class='text-sm mt-2'>If you hire people, you must train them on security. If you don't document the training, it didn't happen.</p>
        ",
        'questions' => [
            [
                'key' => 'c7_storage_location',
                'label' => 'Where are policies stored?',
                'type' => 'text',
                'placeholder' => 'e.g. SharePoint, Google Drive, PurpleWasp Platform',
                'required' => true
            ],
            [
                'key' => 'c7_training_frequency',
                'label' => 'Training Frequency',
                'type' => 'text',
                'placeholder' => 'e.g. On hire and then Annually',
                'required' => true
            ]
        ],
        'template' => "
            <h3>7. Support</h3>
            <h4>7.2 Competence & 7.3 Awareness</h4>
            <p>All employees undergo security awareness training <strong>{{c7_training_frequency}}</strong>. Records of competence are maintained by HR.</p>
            
            <h4>7.5 Documented Information</h4>
            <p>Documented information required by the ISMS is controlled and stored securely in <strong>{{c7_storage_location}}</strong> to ensure availability and confidentiality.</p>
        "
    ],

    // --- CLAUSE 8: OPERATION ---
    'clause_8' => [
        'title' => 'Clause 8: Operation',
        'intro' => 'How do you control daily changes and suppliers?',
        'explanation' => "
            <h4 class='font-bold text-purple-900'>Change Management</h4>
            <p class='text-sm mt-2'>Auditors love to check if code changes are approved. Mention your ticketing system (Jira/Linear).</p>
        ",
        'questions' => [
            [
                'key' => 'c8_change_system',
                'label' => 'Change Management System',
                'type' => 'text',
                'placeholder' => 'e.g. Jira, GitHub, or Linear',
                'required' => true
            ],
            [
                'key' => 'c8_supplier_check',
                'label' => 'Do you review suppliers?',
                'type' => 'text',
                'placeholder' => 'e.g. Yes, we perform due diligence before signing contracts',
                'required' => true,
                'min_length' => 10
            ]
        ],
        'template' => "
            <h3>8. Operation</h3>
            <h4>8.1 Operational Planning and Control</h4>
            <p>Change management is controlled using <strong>{{c8_change_system}}</strong> to ensuring that all changes are reviewed and approved before deployment.</p>
            <p>External processes (suppliers) are controlled as per the Supplier Security Policy: <strong>{{c8_supplier_check}}</strong>.</p>
        "
    ],

    // --- CLAUSE 9: PERFORMANCE ---
    'clause_9' => [
        'title' => 'Clause 9: Performance',
        'intro' => 'How do you check that the system is working?',
        'explanation' => "
            <h4 class='font-bold text-purple-900'>Internal Audit</h4>
            <p class='text-sm mt-2'>You cannot grade your own homework. You must conduct an independent internal audit at least annually.</p>
        ",
        'questions' => [
            [
                'key' => 'c9_audit_frequency',
                'label' => 'Internal Audit Frequency',
                'type' => 'text',
                'placeholder' => 'e.g. Annually',
                'required' => true
            ],
            [
                'key' => 'c9_metrics',
                'label' => 'Key Metrics Monitored',
                'type' => 'textarea',
                'placeholder' => 'e.g. Uptime, Incident Count, Phishing Test Failures',
                'required' => true
            ]
        ],
        'template' => "
            <h3>9. Performance Evaluation</h3>
            <h4>9.1 Monitoring, measurement, analysis and evaluation</h4>
            <p>The organization evaluates the information security performance using metrics such as: <strong>{{c9_metrics}}</strong>.</p>
            
            <h4>9.2 Internal Audit</h4>
            <p>Internal audits are conducted <strong>{{c9_audit_frequency}}</strong> to provide information on whether the ISMS conforms to requirements.</p>
        "
    ],

    // --- CLAUSE 10: IMPROVEMENT ---
    'clause_10' => [
        'title' => 'Clause 10: Improvement',
        'intro' => 'When things go wrong, how do you fix them?',
        'explanation' => "
            <h4 class='font-bold text-purple-900'>Non-Conformity</h4>
            <p class='text-sm mt-2'>When you find a security hole, you must log it. Where is that log kept?</p>
        ",
        'questions' => [
            [
                'key' => 'c10_incident_tracker',
                'label' => 'Where are incidents tracked?',
                'type' => 'text',
                'placeholder' => 'e.g. The Risk Register or Jira',
                'required' => true
            ]
        ],
        'template' => "
            <h3>10. Improvement</h3>
            <h4>10.1 Nonconformity and Corrective Action</h4>
            <p>When a nonconformity occurs, the organization reacts to the nonconformity and takes action to control and correct it.</p>
            <p>All non-conformities and corrective actions are documented and tracked within <strong>{{c10_incident_tracker}}</strong>.</p>
        "
    ]
];