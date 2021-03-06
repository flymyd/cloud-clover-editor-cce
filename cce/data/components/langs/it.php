<?php
/*
 * Cloud Clover Editor
 * Copyright (C) kylon - 2016-2017
 * License - http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * FOLLOW THIS SCHEME FOR TRANSLATIONS
 * 'DO NOT TRANSLATE' => 'TRANSLATE'
 * ANY INVALID TRANSLATION WILL NEVER BE APPROVED
 */

$text = array(
    // DO NOT TRANSLATE
    'cce' => 'Cloud Clover Editor',
    'credit' => 'Cloud Clover Editor'.' '.date('Y').' - kylon',
    'osl_true' => 'Yes',
    'osl_false' => 'No',
    'osl_Apple' => 'Apple',
    'osl_Alternate' => 'Alternate',
    'osl_Theme' => 'Theme',
    'osl_None' => 'None',


    // INDEX
    'upgrade_warning' => 'ATTENZIONE: Questa configurazione è stata creata per una versione precedente di Clover EFI, perciò alcune opzioni non sono presenti su CCE.',
    'upgrade' => 'Aggiorna',
    'select_config' => 'Apri Una Configurazione',
    'create_new_cfg' => 'Nuova Configurazione',
    'load_from_server_cfg' => 'Carica Da CCE Bank',
    'search' => 'Cerca',


    // COMMON
    'save' => 'Salva',
    'download' => 'Download',
    'dsdt' => 'DSDT',
    'ssdt' => 'SSDT',
    'patches' => 'Patch',
    'fixes' => 'Fix',
    'comment' => 'Commento',
    'find' => 'Trova (Hex/Base64)',
    'replace' => 'Sostituisci (Hex/Base64)',
    'disabled' => 'Disabilitato',
    'enabled' => 'Abilitato',
    'status' => 'Stato',
    'options' => 'Opzioni',
    'name' => 'Nome',
    'default_msg' => 'Si è verificato un problema, oppure questa pagina è in sviluppo!',
    'common' => 'Comune',
    'yes' => 'Si',
    'no' => 'No',
    'detect' => 'Rileva',
    'custom' => 'Personalizzato',
    'clear' => 'Pulisci',
    'device' => 'Dispositivo',
    'id' => 'Id',
    'type' => 'Tipo',
    'ram' => 'RAM',
    'legacy' => 'Legacy',
    'copy_to' => 'Copia In',
    'sel_config' => 'Seleziona Config',


    // CCE - tab
    'cce_descr' => 'CCE è una Web App che permette di creare e modificare una configurazione Clover o Ozmosis su qualsiasi OS e dispositivo.<br /><br />
                CCE è scritto in PHP ed è totalmente Open Source.<br /><br />
                Utilizza una versione modificata della libreria CFPropertyList per aprire e salvare una configurazione, mentre utilizza la propria classe per modificarne i valori.',
    'sources' => 'Sorgenti',
    'see_readme' => '(Vedere il README per una lista completa)',
    'usefull_links' => 'Link Utili',
    'open_cfg' => 'Apri Config',
    'new_config' => 'Nuova config',
    'save_file_as' => 'Salva file con nome...',
    'cce_sett_t' => 'Impostazioni CCE',
    'cce_sett_hb64' => 'Mostra i valori Trova/Sostituisci come:',
    'cce_sett_mode' => 'Modalità Cloud Clover Editor (Perderai ogni modifica al file!)',
    'cce_sett_mode_oz' => 'Ozmosis',
    'cce_sett_mode_cce' => 'Clover EFI',
    'save_to_bank' => 'Salva in CCE Bank',
    'bank_edit_mode' => 'Modalità di Modifica',
    'public' => 'Pubblica',
    'private' => 'Privata',
    'bank_edit_key' => 'La tua edit key',
    'invalid_conf_name' => 'Invalido o in uso',


    // ACPI
    'dfix_AddDTGP_0001' => 'Aggiungi DTGP',
    'dfix_FIX_DARWIN_10000' => 'Fixa Darwin',
    'dfix_FixShutdown_0004' => 'Fixa Spegnimento',
    'dfix_AddMCHC_0008' => 'Aggiungi MCHC',
    'dfix_FixHPET_0010' => 'Fixa HPET',
    'dfix_FakeLPC_0020' => 'Falso LPC',
    'dfix_FixIPIC_0040' => 'Fixa IPIC',
    'dfix_FixSBUS_0080' => 'Aggiungi SMBUS',
    'dfix_FixDisplay_0100' => 'Fixa Schermo',
    'dfix_FixIDE_0200' => 'Fixa IDE',
    'dfix_FixSATA_0400' => 'Fixa SATA',
    'dfix_FixFirewire_0800' => 'Fixa Firewire',
    'dfix_FixUSB_1000' => 'Fixa USB',
    'dfix_FixLAN_2000' => 'Fixa LAN',
    'dfix_FixAirport_4000' => 'Fixa AirPort',
    'dfix_FixHDA_8000' => 'Fixa HDA',
    'dfix_FIX_RTC_20000' => 'Fixa RTC',
    'dfix_FIX_TMR_40000' => 'Fixa TMR',
    'dfix_AddIMEI_80000' => 'Aggiungi IMEI',
    'dfix_FIX_INTELGFX_100000' => 'Fixa Intel GFX',
    'dfix_FIX_WAK_200000' => 'Fixa _WAK',
    'dfix_DeleteUnused_400000' => 'Rimuovi Inutilizzati',
    'dfix_FIX_ADP1_800000' => 'Fixa ADP1',
    'dfix_AddPNLF_1000000' => 'Aggiungi PNLF',
    'dfix_FIX_S3D_2000000' => 'Fixa S3D',
    'dfix_FIX_ACST_4000000' => 'Fixa ACST',
    'dfix_AddHDMI_8000000' => 'Aggiungi HDMI',
    'dfix_FixRegions_10000000' => 'Fixa Regioni',
    'misc' => 'Vario',
    'drop_dsm' => 'Scarta OEM _DSM',
    'd_ATI' => 'ATI/AMD GFX',
    'd_NVidia' => 'Nvidia GFX',
    'd_IntelGFX' => 'Intel GFX',
    'd_HDA' => ' PCI HDA',
    'd_HDMI' => 'HDMI Audio',
    'd_IDE' => 'IDE HDD',
    'd_SATA' => 'SATA HDD',
    'd_LAN' => 'PCI LAN',
    'd_WIFI' => 'PCI WIFI',
    'd_LPC' => 'LPC',
    'd_SmBUS' => 'SMBUS',
    'd_USB' => 'USB',
    'd_IMEI' => 'Dispositivo IMEI',
    'd_Firewire' => 'Firewire',
    'dsdt_name' => 'Nome DSDT',
    'fix_mask' => 'Maschera Fix',
    'm_Debug' => 'Debug DSDT',
    'm_ReuseFFFF' => 'Riutilizza FFFF',
    'm_Rtc8Allowed' => 'Consenti Rtc8',
    'm_SuspendOverride' => 'Sovrascrivi Sospensione',
    'a_smartUPS' => 'Smart UPS',
    'a_PatchAPIC' => 'APIC Patch',
    'a_HaltEnabler' => 'Abilita Halt',
    'a_DropMCFG' => 'Scarta MCFG',
    'a_DisableASPM' => 'Disabilita ASPM',
    'ResetAddress' => 'Indirizzo di Reset',
    'ResetValue' => 'Valore di Reset',
    'generate' => 'Genera',
    'ss_PStates' => 'Genera Stati CPU P',
    'ss_CStates' => 'Genera Stati CPU C',
    'ss_DoubleFirstState' => 'Duplica Primo Stato',
    'ss_DropOem' => 'Scarta SSDT OEM (Tutti)',
    'ss_EnableC2' => 'Abilita Stato CPU C2',
    'ss_EnableC4' => 'Abilita Stato CPU C4',
    'ss_EnableC6' => 'Abilita Stato CPU C6',
    'ss_EnableC7' => 'Abilita Stato CPU C7',
    'ss_UseSystemIO' => 'Usa IO di Sistema',
    'ssel_PluginType' => 'Tipo Plugin',
    'ssel_PLimitDict' => 'PLimit Dict',
    'ssel_UnderVoltStep' => 'Livello di UnderVolt',
    'min_mult' => 'Moltiplicatore Min.',
    'max_mult' => 'Moltiplicatore Max.',
    'c3_latency' => 'Latenza C3',
    'acpi' => 'ACPI',
    'drop_tables' => 'Scarta Tablelle',
    'sort_order' => 'Ordine di caricamento',

    // This field does not exist, but you can find it in RehabMan configs. It will not hurt.
    'sort_comment' => 'Commento su Ordine di caricamento',

    'signature' => 'Firma',
    'k_type' => 'Tipo/Chiave',
    'str_numb' => 'Stringa/Numero',
    'dis_aml' => 'AML Disabilitati',


    // Boot
    'boot_title' => 'Opzioni di Avvio',
    'boot_arg' => 'Argomenti di Avvio',
    'def_b_vol' => 'Volume Predefinito',
    'def_loader' => 'Loader Predefinito',
    'lbo_LegacyBiosDefault' => 'Legacy Bios Predefinito',
    'lbo_PBR' => 'PBR',
    'lbo_PBRtest' => 'PBR Test',
    'lbo_PBRsata' => 'PBR Sata',
    'os_logo' => 'Logo',
    'c_logo_placeholder' => 'Percorso o Hex',
    'xmp_det' => 'Rileva XMP',
    'boot_bg_color' => 'Colore Bg di Boot',
    'timeout' => 'Timeout',
    'bb_Debug' => 'Log Debug',
    'bb_Fast' => 'Avvio Rapido',
    'bb_NeverHibernate' => 'Non Ibernare',
    'bb_SkipHibernateTimeout' => 'Salta Timeout Ibernazione',
    'bb_StrictHibernate' => 'Strict Hibernate',
    'bb_NeverDoRecovery' => 'Never Do Recovery',
    'bb_NoEarlyProgress' => 'Nessun Progresso Iniziale',
    'bb_DisableCloverHotkeys' => 'Disabilita Hotkeys Clover',
    'blacklist' => 'Lista Nera',
    'whitelist' => 'Lista Bianca',
    'secure_boot' => 'Avvio Sicuro',
    'secure' => 'Abilita Sicurezza',
    'secure_policy' => 'Metodo di Sicurezza',
    'sec_USER' => 'Utente',
    'sec_DENY' => 'Nega',
    'sec_ALLOW' => 'Consenti',
    'sec_QUERY' => 'Query',
    'sec_INSERT' => 'Inserisci',
    'sec_WHITELIST' => 'Lista Bianca',
    'sec_BLACKLIST' => 'Lista Nera',
    'barg_-v' => 'Modalità testuale (-v)',
    'barg_-x' => 'Modalità sicura (-x)',
    'barg_-s' => 'Modalità utente singolo (-s)',
    'barg_arch=i386' => 'Forza modalità 32bit (arch=i386)',
    'barg_arch=x86_64' => 'Forza modalità 64bit (arch=x86_64)',
    'barg_npci=0x2000' => 'Disabilita flag kIOPCIConfiguratorPFM64 (npci=0x2000)',
    'barg_npci=0x3000' => 'Disabilita flag kIOPCIConfiguratorPFM64 (npci=0x3000)',
    'barg_-f' => 'Ignora cache kext (10.6.x Only) (-f)',
    'barg_dart=0' => 'Disabilita Intel VT-d / VT-x (dart=0)',
    'barg_darkwake=0' => 'Disabilita Power Nap (darkwake=0)',
    'barg_darkwake=1' => 'Abilita Power Nap (darkwake=1)',
    'barg_darkwake=2' => 'Abilita Power Nap modalità 2 (darkwake=2)',
    'barg_darkwake=3' => 'Abilita Power Nap modalità 3 (darkwake=3)',
    'barg_darkwake=4' => 'Abilita Power Nap modalità 4 (darkwake=4)',
    'barg_darkwake=5' => 'Abilita Power Nap modalità 5 (darkwake=5)',
    'barg_darkwake=6' => 'Abilita Power Nap modalità 6 (darkwake=6)',
    'barg_darkwake=7' => 'Abilita Power Nap modalità 7 (darkwake=7)',
    'barg_darkwake=8' => 'Abilita Power Nap modalità 8 (darkwake=8)',
    'barg_darkwake=9' => 'Abilita Power Nap modalità 9 (darkwake=9)',
    'barg_darkwake=10' => 'Abilita Power Nap modalità 10 (darkwake=10)',
    'barg_darkwake=11' => 'Abilita Power Nap modalità 11 (darkwake=11)',
    'barg_cpus=1' => 'Utilizza solo 1 Core della CPU (cpus=1)',
    'barg_slide=0' => 'Fixa Avvio con BIOS AMI UEFI (slide=0)',
    'barg_-xcpm' => 'Abilita Xnu CPU Power Management (-xcpm)',
    'barg_-gux_no_idle' => 'Disabilita parzialmente Intel idle mode (-gux_no_idle)',
    'barg_-gux_nosleep' => 'Sospendi utilizza i metodi spegni/reset (-gux_nosleep)',
    'barg_-gux_nomsi' => 'Forza interruzioni pin al posto dell\' Msi (-gux_nomsi)',
    'barg_-gux_defer_usb2' => 'Sposta la gestione porte USB2 su EHC (-gux_defer_usb2)',
    'barg_nv_disable=1' => 'Disabilita scheda Nvidia (nv_disable=1)',
    'barg_nvda_drv=1' => 'Utilizza i driver Nvidia web (nvda_drv=1)',
    'barg_kext-dev-mode=1' => 'Abilita modalità sviluppatore kext (kext-dev-mode=1)',
    'barg_rootless=0' => 'Abilita privilegi di root (rootless=0)',
    'barg_nv_spanmodepolicy=1' => 'nv_spanmodepolicy=1',
    'barg_keepsyms=1' => 'Mantieni simboli in caso di panic (keepsyms=1)',
    'barg_debug=0x100' => 'Non riavviare in caso di panic (debug=0x100)',
    'barg_kextlog=0xffff' => 'Debug kext (kextlog=0xffff)',
    'barg_-alcoff' => 'Disabilita AppleALC (-alcoff)',
    'barg_-shikioff' => 'Disabilita Shiki (-shikioff)',
    'barg_-liluforce' => 'Carica Lilu in modalità sicura e recovery (-liluforce)',


    // CPU
    'cpu_title' => 'Impostazioni CPU',
    'cpu_frequency' => 'Frequenza (MHz)',
    'bus_speed' => 'Velocità Bus (kHz)',
    'qpi' => 'QPI',
    'saving_mode' => 'Modalità di Salvataggio',
    'cpuC_QEMU' => 'QEMU',
    'cpuC_TurboDisable' => 'Disabilita Turbo Boost',
    'cpuC_UseARTFrequency' => 'Usa Frequenza ART',
    'cpuC_HWPEnable' => 'Abilita HWP',
    'hwp_value' => 'Valore HWP',
    'tdp_value' => 'Valore TDP',


    // Devices
    'fake_id' => 'Falsifica ID',
    'lan' => 'LAN',
    'sata' => 'SATA',
    'wifi' => 'WIFI',
    'xhci' => 'XHCI',
    'imei' => 'IMEI',
    'devices' => 'Dispositivi',
    'usb' => 'USB',
    'usb_Inject' => 'Inietta',
    'usb_AddClockID' => 'Aggiungi ID Clock',
    'usb_FixOwnership' => 'Fixa Permessi',
    'usb_HighCurrent' => 'Alta Corrente',
    'usb_NameEH00' => 'Rinomina in EHxx',
    'audio' => 'Audio',
    'inject' => 'Inietta',
    'layout_id' => 'ID Layout',
    'aud_AFGLowPowerState' => 'AFG Modalià Basso Consumo',
    'aud_ResetHDA' => 'Resetta HDA',
    'dvc_Inject' => 'Inietta Proprietà',
    'dvc_UseIntelHDMI' => 'Usa Intel HDMI',
    'dvc_ForceHPET' => 'Forza HPET',
    'dvc_NoDefaultProperties' => 'Nessuna Proprietà di Default',
    'dvc_SetIntelBacklight' => 'Setta Luminosità Intel',
    'dvc_DisableFunctions' => 'Disabilita Funzioni',
    'properties' => 'Proprietà',
    'add_prop' => 'Aggiungi Proprietà',
    'key' => 'Chiave',
    'value' => 'Valore',
    'custom_props' => 'Proprietà Personalizzate',
    'pci_addr' => 'PCI Addr',


    // Disable Drivers
    'disdrv_title' => 'Disabilita Driver',
    'driver' => 'Driver',


    // GUI
    'bl_gui_sett' => 'Impostazioni GUI Bootloader',
    'scan_opt' => 'Opzioni Scansione',
    'scan' => 'Scansione',
    'scan_auto' => 'Automatica',
    'scan_entries' => 'Scan. Voci Bootloader',
    'scan_linux' => 'Scan. Linux',
    'scan_tools' => 'Scan. Strumenti',
    'scan_kernel' => 'Scan. Kernel',
    'scan_legacy' => 'Scan. Legacy',
    'lscn_First' => 'Primo',
    'lscn_ ' => 'Disabilitata', // Ugly workaround PHP 5.3
    'kscn_All' => 'Tutti i kernel',
    'kscn_Oldest' => 'Data di modifica più vecchia',
    'kscn_First' => 'Primo Trovato',
    'kscn_Last' => 'Ultimo Trovato',
    'kscn_MostRecent' => 'Versione Più Recente',
    'kscn_Earliest' => 'Versione Iniziale',
    'kscn_ ' => 'Disabilitata', // Ugly workaround PHP 5.3
    'mouse' => 'Mouse',
    'dbl_clk' => 'Doppio Click',
    'speed' => 'Velocità',
    'mirror' => 'Mirror',
    'language' => 'Lingua',
    'screen_res' => 'Risoluzione Schermo',
    'console_mode' => 'Modalità Console',
    'theme' => 'Tema',
    'custom_icons' => 'Icone Personalizzate',
    'text_only' => 'Solo Testo',
    'bl_entries_sett' => 'Impostazioni Voci Bootloader',
    'custom_entr' => 'Voci Personalizzate',
    'custom_legacy_entr' => 'Voci Legacy Personalizzate',
    'custom_tool_entr' => 'Voci Strumenti Personalizzate',
    'hide_vols' => 'Nascondi Volumi',
    'volume' => 'Volume',
    'title' => 'Titolo',
    'full_title' => 'Solo Titolo',
    'hotkey' => 'Hotkey',
    'hidden' => 'Nascosto',
    'arguments' => 'Argomenti',
    'path' => 'Percorso',
    'cesl_OSXRecovery' => 'OS X Recovery',
    'cesl_OSXInstaller' => 'OS X Installer',
    'cesl_OSX' => 'OS X',
    'cesl_Windows' => 'Windows',
    'cesl_Linux' => 'Linux',
    'cesl_LinuxKernel' => 'Linux Kernel',
    'add_arguments' => 'Aggiungi Argomenti',
    'ceop_Disabled' => 'Disabilitato',
    'ceop_NoCaches' => 'No Cache',
    'sub_entries' => 'Voci Secondarie',
    'common_settings' => 'Impostazioni Comuni',
    'always' => 'Sempre',
    'image' => 'Immagine',
    'drive_image' => 'Immagine Drive',
    'volume_type' => 'Tipo di Volume',
    'volt_Internal' => 'Interno',
    'volt_External' => 'Esterno',
    'volt_Optical' => 'Ottico',
    'volt_FireWire' => 'FireWire',


    // Graphics
    'gfx_patch' => 'Patch Grafiche',
    'edid_patch' => 'Patch EDID',
    'edd_Inject' => 'Inietta',
    'edid_vendor_id' => 'ID Fornitore',
    'edid_product_id' => 'ID Produttore',
    'custom_edid' => 'Personalizzato (Hex)',
    'inj_ATI' => 'ATI',
    'inj_Intel' => 'Intel',
    'inj_NVidia' => 'Nvidia',
    'gfc_LoadVBios' => 'Carica VBios',
    'gfc_PatchVBios' => 'Patcha VBios',
    'gfc_NvidiaGeneric' => 'Nvidia Generic',
    'gfc_NvidiaSingle' => 'Nvidia Single',
    'gfc_NvidiaNoEFI' => 'Nvidia senza EFI',
    'dual_link' => 'Dual Link',
    'fb_name' => 'Nome FrameBuffer',
    'nvcap' => 'NVCAP',
    'vram' => 'VRAM',
    'vports' => 'Porte Video',
    'display_cfg' => 'Visualizza CFG',
    'ig_platform_id' => 'ID Piattaforma IG',
    'snb_platform_id' => 'ID Piattaforma SNB',
    'boot_display' => 'Schermo di Avvio',
    'ref_clk' => 'Ref CLK',
    'vbios_patch' => 'Patch VBios',
    'mult_gfx_card_injection' => 'Inietta Schede Grafiche Multiple',
    'model' => 'Modello',
    'iopci_primary_match' => 'Primo Riscontro IOPCI',
    'iopci_sub_dev_id' => 'ID Dispositivo Secondario IOPCI',


    // Kernel and Kext Patches
    'kernel_patch' => 'Patch Kernel',
    'ati_con_data' => 'Dati Connettori ATI',
    'ati_con_patch' => 'Patch Connettori ATI',
    'kkp_AppleRTC' => 'Apple RTC',
    'kkp_AsusAICPUPM' => 'Asus AICPUPM',
    'kkp_Debug' => 'Debug',
    'kkp_KernelCpu' => 'Kernel CPU',
    'kkp_KernelPm' => 'Kernel PM',
    'kkp_KernelLapic' => 'Kernel Lapic',
    'kkp_KernelHaswellE' => 'Kernel Haswell-E',
    'kkp_DellSMBIOSPatch' => 'Fixa SMBIOS Dell',
    'fake_cpu_id' => 'Falsifica ID CPU',
    'ati_con_controller' => 'Controller Connettori ATI',
    'kext_patch' => 'Patch Kext',
    'kext_to_patch' => 'Kext da Patchare',
    'kernel_to_patch' => 'Kernel da Patchare',
    'match_oses' => 'OS da Patchare',
    'match_build' => 'Build da Patchare',
    'kopt_InfoPlistPatch' => 'Patcha Info Plist',
    'kopt_Disabled' => 'Disabilitata',
    'krno_Disabled' => 'Disabilitata',
    'befo_Disabled' => 'Disabilitata',
    'force_kext' => 'Forza Caricamento Kext',
    'boot_efi_patches' => 'Patch per Boot.efi',
    'osv_10.6.x' => 'Mac OS X Snow Leopard (10.6.x)',
    'osv_10.6' => 'Mac OS X Snow Leopard (10.6)',
    'osv_10.6.1' => 'Mac OS X Snow Leopard Upd 1 (10.6.1)',
    'osv_10.6.2' => 'Mac OS X Snow Leopard Upd 2 (10.6.2)',
    'osv_10.6.3' => 'Mac OS X Snow Leopard Upd 3 (10.6.3)',
    'osv_10.6.4' => 'Mac OS X Snow Leopard Upd 4 (10.6.4)',
    'osv_10.6.5' => 'Mac OS X Snow Leopard Upd 5 (10.6.5)',
    'osv_10.6.6' => 'Mac OS X Snow Leopard Upd 6 (10.6.6)',
    'osv_10.6.7' => 'Mac OS X Snow Leopard Upd 7 (10.6.7)',
    'osv_10.6.8' => 'Mac OS X Snow Leopard Upd 8 (10.6.8)',
    'osv_10.7.x' => 'Mac OS X Lion (10.7.x)',
    'osv_10.7' => 'Mac OS X Lion (10.7)',
    'osv_10.7.1' => 'Mac OS X Lion Upd 1 (10.7.1)',
    'osv_10.7.2' => 'Mac OS X Lion Upd 2 (10.7.2)',
    'osv_10.7.3' => 'Mac OS X Lion Upd 3 (10.7.3)',
    'osv_10.7.4' => 'Mac OS X Lion Upd 4 (10.7.4)',
    'osv_10.7.5' => 'Mac OS X Lion Upd 5 (10.7.5)',
    'osv_10.8.x' => 'Mac OS X Mountain Lion (10.8.x)',
    'osv_10.8' => 'Mac OS X Mountain Lion (10.8)',
    'osv_10.8.1' => 'Mac OS X Mountain Lion Upd 1 (10.8.1)',
    'osv_10.8.2' => 'Mac OS X Mountain Lion Upd 2 (10.8.2)',
    'osv_10.8.3' => 'Mac OS X Mountain Lion Upd 3 (10.8.3)',
    'osv_10.8.4' => 'Mac OS X Mountain Lion Upd 4 (10.8.4)',
    'osv_10.8.5' => 'Mac OS X Mountain Lion Upd 5 (10.8.5)',
    'osv_10.9.x' => 'Mac OS X Mavericks (10.9.x)',
    'osv_10.9' => 'Mac OS X Mavericks (10.9)',
    'osv_10.9.1' => 'Mac OS X Mavericks Upd 1 (10.9.1)',
    'osv_10.9.2' => 'Mac OS X Mavericks Upd 2 (10.9.2)',
    'osv_10.9.3' => 'Mac OS X Mavericks Upd 3 (10.9.3)',
    'osv_10.9.4' => 'Mac OS X Mavericks Upd 4 (10.9.4)',
    'osv_10.9.5' => 'Mac OS X Mavericks Upd 5 (10.9.5)',
    'osv_10.10.x' => 'Mac OS X Yosemite (10.10.x)',
    'osv_10.10' => 'Mac OS X Yosemite (10.10)',
    'osv_10.10.1' => 'Mac OS X Yosemite Upd 1 (10.10.1)',
    'osv_10.10.2' => 'Mac OS X Yosemite Upd 2 (10.10.2)',
    'osv_10.10.3' => 'Mac OS X Yosemite Upd 3 (10.10.3)',
    'osv_10.10.4' => 'Mac OS X Yosemite Upd 4 (10.10.4)',
    'osv_10.10.5' => 'Mac OS X Yosemite Upd 5 (10.10.5)',
    'osv_10.11.x' => 'Mac OS X El Capitan (10.11.x)',
    'osv_10.11' => 'Mac OS X El Capitan (10.11)',
    'osv_10.11.1' => 'Mac OS X El Capitan Upd 1 (10.11.1)',
    'osv_10.11.2' => 'Mac OS X El Capitan Upd 2 (10.11.2)',
    'osv_10.11.3' => 'Mac OS X El Capitan Upd 3 (10.11.3)',
    'osv_10.11.4' => 'Mac OS X El Capitan Upd 4 (10.11.4)',
    'osv_10.11.5' => 'Mac OS X El Capitan Upd 5 (10.11.5)',
    'osv_10.11.6' => 'Mac OS X El Capitan Upd 6 (10.11.6)',
    'osv_10.12.x' => 'macOS Sierra (10.12.x)',
    'osv_10.12' => 'macOS Sierra (10.12)',
    'osv_10.12.1' => 'macOS Sierra Upd 1 (10.12.1)',
    'osv_10.12.2' => 'macOS Sierra Upd 2 (10.12.2)',
    'osv_10.12.3' => 'macOS Sierra Upd 3 (10.12.3)',
    'osv_10.12.4' => 'macOS Sierra Upd 4 (10.12.4)',
    'osv_10.12.5' => 'macOS Sierra Upd 5 (10.12.5)',
    'osv_10.12.6' => 'macOS Sierra Upd 6 (10.12.6)',
    'osv_10.13' => 'macOS High Sierra (10.13)',


    // RT Variables
    'rt_var_title' => 'Variabili RT',
    'mlb' => 'MLB',
    'rom' => 'ROM',
    'booter_config' => 'Valore Booter',
    'csr_config' => 'Valore Csr',
    'csr_modal_title' => 'Flag SIP',
    'csrf_kext' => 'Consenti Kexts Sconosciuti',
    'csrf_fs' => 'Consenti FS Non Ristretto',
    'csrf_pid' => 'Consenti Task For PID',
    'csrf_kernel' => 'Consenti il Kernel Debugger',
    'csrf_apple' => 'Consenti Apple Internals',
    'csrf_dtrace' => 'Consenti DTrace Non Ristretto',
    'csrf_nvram' => 'Consenti NVRAM Non Ristretta',
    'csrf_device' => 'Consenti Configurazione Dispositivo',
    'csrf_basesys' => 'Disabilita Verifica Sistema Base',
    'booter_modal_title' => 'Flag boot.efi',
    'btr_reboot' => 'Riavvia in caso di panico',
    'btr_dpi' => 'Alto DPI',
    'btr_black_screen' => 'Schermo Nero',
    'btr_csra' => 'Valore CSR Attivo',
    'btr_csrp' => 'Valore CSR in Attesa',
    'btr_csrb' => 'Valore CSR di Boot',
    'btr_black_bg' => 'Sfondo Nero',
    'btr_login' => 'UI Login',
    'btr_install' => 'UI Installazione',


    // SMBIOS
    'smbios' => 'SMBIOS',
    'sel_model' => 'Seleziona Mac',
    'cpu' => 'CPU',
    'gfx_card' => 'Scheda Grafica',
    'no_specs' => 'Impossibile trovare info su questo Mac',
    'pd_name' => 'Nome Prodotto',
    'pd_family' => 'Famiglia',
    'manufacturer' => 'Produttore',
    'bios_ver' => 'Versione Bios',
    'bios_rel_date' => 'Data Rilascio Bios',
    'bios_vendor' => 'Produttore Bios',
    'fw_features' => 'Funzioni Firmware',
    'fw_feature_mask' => 'Firmware Features Mask',
    'version' => 'Versione',
    'board_id' => 'ID Mobo',
    'board_manufacturer' => 'Produttore Mobo',
    'board_ver' => 'Versione Mobo',
    'board_sn' => 'Seriale Scheda Madre',
    'board_type' => 'Tipo Scheda Madre',
    'bdt_1' => 'Sconosciuto',
    'bdt_2' => 'Altro',
    'bdt_3' => 'Server Blade',
    'bdt_4' => 'Connectivity Switch',
    'bdt_5' => 'System Management Module',
    'bdt_6' => 'Processor Module',
    'bdt_7' => 'IO Module',
    'bdt_8' => 'Memory Module',
    'bdt_9' => 'Daughter Board',
    'bdt_10' => 'Mother Board',
    'bdt_11' => 'Processor Memory Module',
    'bdt_12' => 'Processor IO Module',
    'bdt_13' => 'Interconnect Board',
    'chass_manufacturer' => 'Produttore Scocca',
    'loc_in_chass' => 'Posizione nella Scocca',
    'chass_asset' => 'Tag Scocca',
    'chass_type' => 'Tipo di Scocca',
    'cht_01' => 'Altro',
    'cht_02' => 'Sconosciuto',
    'cht_03' => 'Desktop',
    'cht_04' => 'Low Profile Desktop',
    'cht_05' => 'Pizza Box',
    'cht_06' => 'Mini Tower',
    'cht_07' => 'Tower',
    'cht_08' => 'Portable',
    'cht_09' => 'Laptop',
    'cht_0A' => 'Notebook',
    'cht_0B' => 'Hand Held',
    'cht_0C' => 'Docking Station',
    'cht_0D' => 'All In One',
    'cht_0E' => 'Sub Notebook',
    'cht_0F' => 'Space-Saving',
    'cht_10' => 'Lunch Box',
    'cht_11' => 'Main Server Chassis',
    'cht_12' => 'Expansion Chassis',
    'cht_13' => 'SubChassis',
    'cht_14' => 'Bus Expansion Chassis',
    'cht_15' => 'Peripheral Chassis',
    'cht_16' => 'Raid Chassis',
    'cht_17' => 'Rack Mount Chassis',
    'cht_18' => 'Sealed-Case Pc',
    'cht_19' => 'Multi-System Chassis',
    'cht_1A' => 'Compact PCI',
    'cht_1B' => 'Advanced TCA',
    'cht_1C' => 'Blade',
    'cht_1D' => 'Blade Enclosure',
    'serial_numb' => 'Numero Seriale',
    'sm_uuid' => 'SM UUID',
    'plat_feature' => 'Funzioni Piattaforma',
    'sch_Mobile' => 'Portatile',
    'sch_Trust' => 'Segna come Affidabile',
    'manufacturer_loc' => 'Luogo di Produzione',
    'mnl_CK' => 'Cork, Irlanda',
    'mnl_CY' => 'Korea',
    'mnl_PT' => 'Korea (Alt)',
    'mnl_FC' => 'Fountain, Colorado',
    'mnl_G8' => 'USA',
    'mnl_QP' => 'USA (Alt)',
    'mnl_XA' => 'Elk Grove, California',
    'mnl_XB' => 'Elk Grove, California (Alt)',
    'mnl_QT' => 'Taiwan',
    'mnl_UV' => 'Taiwan (Alt)',
    'mnl_V7' => 'Taiwan (Alt-2)',
    'mnl_RN' => 'Messico',
    'mnl_RM' => 'Messico (Alt)',
    'mnl_SG' => 'Singapore',
    'mnl_W8' => 'Cina',
    'mnl_YM' => 'Cina (Alt)',
    'manufacturer_yr' => 'Anno di Produzione',
    'manufacturer_wk' => 'Settimana di Produzione',
    'model_code' => 'Codice Modello',
    'u_number' => 'Numbero Unità',
    'shake' => 'Cambia',
    'serial_gen' => 'Genera Numero Seriale',
    'ram_modules' => 'Moduli RAM',
    'channels' => 'Canali',
    'ch_single' => 'Un Canale',
    'ch_dual' => 'Due Canale',
    'ch_triple' => 'Tre Canali',
    'ch_quad' => 'Quattro Canali',
    'slot_count' => 'Numero di Slot',
    'ram_inj' => 'Inietta Tabelle RAM',
    'slot' => 'Slot',
    'sizeM' => 'Grandezza (MB)',
    'freqM' => 'Frequenza (MHz)',
    'vendor' => 'Produttore',
    'part' => 'Parte',
    'serial' => 'Seriale',
    'slots' => 'Slot',


    // System Parameters
    'sysparam_title' => 'Parametri di Sistema',
    'light_lev' => 'Livello di Luminosità',
    'custom_uuid' => 'UUID Personalizzato',
    'inject_kext' => 'Inietta Kext',
    'syp_NoCaches' => 'No Cache',
    'syp_InjectSystemID' => 'Inietta ID di Systema',
    'syp_ExposeSysVariables' => 'Mostra Variabili di Sistema',
    'syp_NvidiaWeb' => 'Usa Driver Nvidia Web',


    // Boot Graphics
    'bootgfx_title' => 'Grafica Boot',
    'def_bg_color' => 'Colore di Sfondo Predefinito',
    'ui_scale' => 'UI Scale',
    'efi_login_hi_dpi' => 'EFI Login Hi DPI',
    'flagstate' => 'Flag di stato',


    // Ozmosis
    'ozmosis_title' => 'Ozmosis',
    'oz_smbios_sec' => 'GUID: 4D1FDA02-38C7-4A6A-9CC6-4BCCA8B30102',
    'oz_smbios_see_more' => '(Vedi SMBIOS per altre opzioni)',
    'oz_sys_sku' => 'SKU Sistema',
    'oz_fw_rev' => 'Revisione Firmware',
    'oz_hw_addr' => 'Hardware Address(ROM)',
    'oz_board_ass_tag' => 'Base Board Asset Tag',
    'oz_gfx_sec' => 'GUID: 1F8E0C02-58A9-4E34-AE22-2B63745FA101',
    'oz_acpi_loader' => 'Modalità Acpi',
    'oz_acpi_loader_modal_title' => 'Mod. Loader ACPI',
    'ozal_0' => 'ACPI_LOADER_MODE_DISABLE',
    'ozal_1' => 'ACPI_LOADER_MODE_ENABLE',
    'ozal_2' => 'ACPI_LOADER_MODE_DUMP',
    'ozal_4' => 'ACPI_LOADER_MODE_DARWIN',
    'ozal_8' => 'ACPI_LOADER_MODE_WINDOWS',
    'ozal_40' => 'ACPI_LOADER_MODE_UPDATE_LEGACY',
    'oz_ati_fb' => 'Framebuffer Ati',
    'oz_snb_pid' => 'AAPL,snb_platform_id',
    'oz_ig_pid' => 'AAPL,ig-platform-id',
    'oz_disk_title' => 'Tipo di disco',
    'oz_templ_title' => 'Tipo di template',
    'ozdt_BootEntryTemplate' => 'Voce di boot',
    'ozdt_DarwinDiskTemplate' => 'Disco Darwin',
    'ozdt_DarwinRecoveryDiskTemplate' => 'Disco di recovery Darwin',
    'ozdt_DarwinCoreStorageTemplate' => 'Darwin core storage',
    'ozdt_AndroidDiskTemplate' => 'Disco Android',
    'ozdt_AndroidDiskOptionTemplate' => 'Argomenti di boot per Android',
    'ozdt_LinuxDiskTemplate' => 'Disco Linux',
    'ozdt_LinuxDiskOptionTemplate' => 'Argomenti di boot per Linux',
    'ozdt_LinuxRescueDiskTemplate' => 'Disco distro di recupero Linux',
    'ozdt_LinuxRescueOptionTemplate' => 'Argomenti di boot per distro di recupero Linux',
    'ozt_$label' => '$label',
    'ozt_$guid' => '$guid',
    'ozt_$uuid' => '$uuid',
    'ozt_$platform' => '$platform',
    'ozt_$major' => '$major',
    'ozt_$minor' => '$minor',
    'ozt_$build' => '$build',
    'oz_clear_template' => 'Pulisci template',
    'ozg_DisableAtiInjection' => 'Disabilita Ati Injection',
    'ozg_DisableNvidaInjection' => 'Disabilita Nvida Injection',
    'ozg_DisableIntelInjection' => 'Disabilita Intel Injection',
    'ozg_DisableVoodooHda' => 'Disabilita VoodooHda',
    'ozg_EnableVoodooHdaInternalSpdif' => 'Abilita VoodooHda Internal S/PIDF',
    'ozg_DisableBootEntriesFilter' => 'Disabilita Boot Entries Filter',
    'ozg_UserInterface' => 'Mostra Interfacci Utente',
    'oz_boot_arg_sec' => 'GUID: 7C436110-AB2A-4BBB-A880-FE41995C9F82',
    'oz_boot_see_more' => '(Vedi RT Variables per altre opzioni)'
);
