<?php

/*-----------SECTIONS------------------*/
const SECTION_A_VISITOR_DETAILS = 'section_a_visitor_details';
const SECTION_B_ACCESS_LEVEL = 'section_b_access_level';
const SECTION_C_SITE_SPONSORS_APPROVAL = 'section_c_site_sponsors_approval';
const SECTION_D_SITE_ACCESS_APPROVAL = 'section_d_site_access_approval';
const SECTION_E_VISITORS_DECLARATION = 'section_e_visitors_declaration';
const SECTION_F_HOST_APPROVAL = 'section_f_host_approval';

const SECTION_ORDER = [
    SECTION_A_VISITOR_DETAILS,
    SECTION_B_ACCESS_LEVEL,
    SECTION_C_SITE_SPONSORS_APPROVAL,
    SECTION_D_SITE_ACCESS_APPROVAL,
    SECTION_E_VISITORS_DECLARATION,
    SECTION_F_HOST_APPROVAL
];

const USER_ROLE_NORMAL_LEVEL = 1;
const USER_ROLE_APPROVER_LEVEL = 2;
const USER_ROLE_SYSTEM_ADMIN_LEVEL = 3;

const HTTP_PDF_DOWNLOAD_URL = 'http://' . ':3000/download';
