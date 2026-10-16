-- ============================================================
--  AAUJMR — Journal data seed
--  Site settings use ON DUPLICATE KEY UPDATE. Editorial board data is
--  reset below so ordering matches this supplied seed; back up custom board data first.
--  Source: AAUJMR journal data
-- ============================================================

-- ─── Site Settings ──────────────────────────────────────────────────────────
INSERT INTO `site_settings` (`key`, `value`) VALUES
  ('editor_name',        'Prof. Eshiebo Irekpita'),
  ('contact_email',      'aaujmr@aauekpoma.edu.ng'),
  ('submission_email',   'aaujmr@aauekpoma.edu.ng'),
  ('submission_email_secondary', 'editoraaujmr@gmail.com'),
  ('contact_phone',      ''),
  ('whatsapp_url',       ''),
  ('contact_address',    'Office of the Dean, Faculty of Basic Medical Sciences,\nAmbrose Alli University, Ekpoma, Nigeria.'),
  ('social_twitter',     ''),
  ('social_linkedin',    ''),
  ('social_whatsapp',    ''),
  ('pub_frequency',      'Annually'),
  ('publication_month',  'June')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

-- ─── Editorial Board ────────────────────────────────────────────────────────
-- Truncate and repopulate so ordering stays clean.
-- WARNING: This removes any manually added board members. Back up first if needed.
TRUNCATE TABLE `editorial_board`;

INSERT INTO `editorial_board` (`name`, `role`, `affiliation`, `display_order`, `active`) VALUES
  -- Editor-in-Chief
  ('Prof. Eshiebo Irekpita',    'Editor-in-Chief',        'Ambrose Alli University, Ekpoma',  10, 1),
  -- Managing Editor
  ('Dr. Oluwafemi O. Awe',      'Managing Editor',        'Ambrose Alli University, Ekpoma',  20, 1),
  -- Editors
  ('Dr. Andrew Dongo',          'Editor',                 '',                                  30, 1),
  ('Dr. Emeka Kesieme',         'Editor',                 '',                                  31, 1),
  ('Dr. Chiedozie Ike',         'Editor',                 '',                                  32, 1),
  ('Dr. Osahon Otaigbe',        'Editor',                 '',                                  33, 1),
  -- Editorial Board Members
  ('Prof. J.E. Onuminya',       'Editorial Board Member', 'Orthopedics',                       40, 1),
  ('Prof. S. Olowo Samuel',     'Editorial Board Member', 'Medical Microbiology',              41, 1),
  ('Prof. T.A.T. Salami',       'Editorial Board Member', 'Internal Medicine',                 42, 1),
  ('Prof. S. Okogbenin',        'Editorial Board Member', 'Obstetrics & Gynecology',           43, 1),
  ('Prof. M. Odike',            'Editorial Board Member', 'Anatomic Pathology',                44, 1),
  ('Prof. M. Enoch',            'Editorial Board Member', 'Ophthalmology',                     45, 1),
  ('Prof. J. Kpolugbo',         'Editorial Board Member', 'General Surgery',                   46, 1),
  ('Prof. D. Asogun',           'Editorial Board Member', 'Community Medicine',                47, 1),
  -- Editorial Advisers
  ('Prof. G.O. Akpede',         'Editorial Adviser',      'AAU, Ekpoma',                       50, 1),
  ('Prof. Ewan Alufohai',       'Editorial Adviser',      'AAU, Ekpoma',                       51, 1),
  ('Prof. F. Okogbo',           'Editorial Adviser',      'AAU, Ekpoma',                       52, 1),
  ('Prof. G. Iyalome',          'Editorial Adviser',      'AAU, Ekpoma',                       53, 1),
  ('Prof. A. Igbe',             'Editorial Adviser',      'AAU, Ekpoma',                       54, 1),
  ('Prof. P. Aloamaka',         'Editorial Adviser',      'DELSU, Abraka',                     55, 1),
  ('Prof. M. Ramiyil',          'Editorial Adviser',      'University of Jos',                 56, 1),
  ('Prof. A. Sule',             'Editorial Adviser',      'University of Jos',                 57, 1),
  ('Prof. O.M. Oluwatosin',     'Editorial Adviser',      'University of Ibadan',              58, 1),
  ('Prof. F.E. Okonofua',       'Editorial Adviser',      'University of Benin',               59, 1),
  ('Prof. A.B. Ebeigbe',        'Editorial Adviser',      'University of Benin',               60, 1),
  ('Prof. V. Odigie',           'Editorial Adviser',      'University of Benin',               61, 1),
  ('Prof. I. Abdu-Aguaye',      'Editorial Adviser',      'KADSU, Kaduna',                     62, 1),
  ('Prof. M.T. Shokunbi',       'Editorial Adviser',      'University of Ibadan',              63, 1),
  ('Prof. P.O. Obekpa',         'Editorial Adviser',      'University of Jos',                 64, 1),
  ('Prof. M.I. Oginni',         'Editorial Adviser',      'OAU, Ile-Ife',                      65, 1),
  ('Prof. O.O. Mbonu',          'Editorial Adviser',      'NAU, Akwa',                         66, 1),
  ('Prof. O. Onuba',            'Editorial Adviser',      'University of Calabar',             67, 1),
  ('Prof. F.C. Akpuaka',        'Editorial Adviser',      'Anambra State University',          68, 1),
  ('Prof. C. Osime',            'Editorial Adviser',      'University of Benin',               69, 1),
  ('Dr. S.O. Dawodu',           'Editorial Adviser',      'ISTH, Irrua',                       70, 1),
  -- Editorial Secretary
  ('Ijeh-Ogboi Vivian',         'Editorial Secretary',    '',                                  80, 1);
