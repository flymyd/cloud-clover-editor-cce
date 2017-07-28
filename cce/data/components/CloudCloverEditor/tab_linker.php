<?php
/*
 * Cloud Clover Editor
 * Copyright (C) kylon - 2016-2017
 * License - http://www.gnu.org/licenses/gpl-3.0.txt
 */

require_once 'cloverPlist.php';

require_once 'tabs/cce.php';
require_once 'tabs/acpi.php';
require_once 'tabs/boot.php';
require_once 'tabs/cpu.php';
require_once 'tabs/devices.php';
require_once 'tabs/disable_drivers.php';
require_once 'tabs/gui.php';
require_once 'tabs/graphics.php';
require_once 'tabs/kernel_and_kext_patches.php';
require_once 'tabs/rtvariables.php';
require_once 'tabs/smbios.php';
require_once 'tabs/system_parameters.php';
require_once 'tabs/boot_graphics.php';
require_once 'tabs/ozmosis.php';

$config = unserialize($_SESSION['clover']);

$menuEntry = array(
    // ICON => TEXT
    'cloudclovereditor' => $text['cce'],
    'power-off' => 'ACPI',
    'terminal' => 'Boot',
    'cpu' => 'CPU',
    'hdd-o' => 'Devices',
    'ban' => 'Disable Drivers',
    'columns' => 'GUI',
    'paint-brush' => 'Graphics',
    'wrench' => 'Kernel And Kext Patches',
    'superscript' => 'RT Variables',
    'laptop' => 'SMBIOS',
    'cog' => 'System Parameters',
    'palette' => 'Boot Graphics',
    'microchip' => 'Ozmosis'
);

/**
 * Draw a patch table.
 *
 * @param string $type
 * @param array $th
 * @param array|null $patches
 * @param array $extra
 */
function drawPatchTable($type='', $th=array(), $patches=null, $extra=array()) {
    global $text;
    $i=0; ?>
    <div class="row">
        <div class="col-md-12">
            <div data-id="add-<?php echo $type; ?>" class="addTbBtn bg-green-flat"><i class="fa icon-plus"></i> </div>

            <?php if ($type != 'cPropS') { ?>
            <div data-id="cp-<?php echo $type; ?>" class="cpTbBtn bg-yellow-flat"><i class="fa icon-files-o"></i> </div>
            <?php } ?>

            <div data-id="del-<?php echo $type; ?>" class="delTbBtn bg-red-flat"><i class="fa icon-trash"></i> </div>
        </div>
    </div>

    <div class="table-scroll">
        <table class="patch-table <?php echo $type; ?>">
            <tr>
                <td>
                    <table>
                        <tr>
                            <?php foreach ($th as $thead) { ?>
                                <th><?php echo $thead; ?></th>
                            <?php } ?>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="table-scrollbody">
                        <table class="<?php echo $type; ?>">
                            <tbody class="table-cont">
                            <?php if ($patches !== null) {
                                switch ($type) {
                                    case 'dsdtP':
                                        foreach ($patches as $p) { ?>
                                            <tr data-path="ACPI/DSDT/Patches/<?php echo $i; ?>" data-dpath="ACPI/DSDT/Patches" data-index="<?php echo $i; ?>">
                                                <td class="inline-text" data-field="Comment"><?php echo getPropVal($p, 'Comment'); ?></td>
                                                <td class="inline-text" data-field="Find"><?php echo getPropVal($p, 'Find'); ?></td>
                                                <td class="inline-text" data-field="Replace"><?php echo getPropVal($p, 'Replace'); ?></td>
                                                <td>
                                                    <input class="cce-checkbox" data-field="Disabled" type="checkbox" <?php echo isChecked($p, 'Disabled'); ?> />
                                                </td>
                                            </tr>
                                            <?php ++$i; }
                                        break;
                                    case 'ssdtTb':
                                        $signatures = array('APIC','BGRT','CSRT','DMAR','ECDT','FACP','FPDT','HPET','LPIT','MCFG','MSDM','SLIC','SSDT','TCPA','TPM2');
                                        $types = array('TableId','Length');

                                        foreach ($patches as $p) {
                                            $fieldSel = $isSsdt = '';
                                            $dataFieldKeyVal = 'SSDTkeyVal';

                                            if ($p['Signature'] === 'SSDT') {
                                                $isSsdt = true;

                                                if (isset($p['TableId'])) $fieldSel = $dataFieldKeyVal = 'TableId';
                                                if (isset($p['Length'])) $fieldSel = $dataFieldKeyVal = 'Length';

                                                $value = ($fieldSel != 'TableId' && $fieldSel != 'Length') ? '': ( ($fieldSel == 'TableId') ? $p['TableId']:$p['Length'] );
                                            } ?>
                                            <tr data-path="ACPI/DropTables/<?php echo $i; ?>" data-dpath="ACPI/DropTables" data-index="<?php echo $i; ?>">
                                                <td>
                                                    <select class="cce-sel select-inTable dropTbSel" data-path="ACPI/DropTables/<?php echo $i; ?>" data-field="Signature">
                                                        <?php foreach ($signatures as $sign) { ?>
                                                            <option value="<?php echo $sign; ?>" <?php echo isSelected($sign, $p['Signature']); ?>><?php echo $sign; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <?php if ($isSsdt) { ?>
                                                        <select class="cce-sel select-inTable dropTbSel" data-path="ACPI/DropTables/<?php echo $i; ?>" data-field="SSDTkey">
                                                            <?php
                                                            if ($value === '') echo '<option value=""></option>';

                                                            foreach ($types as $t) { ?>
                                                                <option value="<?php echo $t; ?>" <?php echo isSelected($t, $fieldSel); ?>><?php echo $t; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } ?>
                                                </td>
                                                <td <?php echo $isSsdt ? 'class="inline-text"':'' ?> data-field="<?php echo $dataFieldKeyVal; ?>"><?php if ($isSsdt) { echo $value; } ?></td>
                                            </tr>
                                            <?php ++$i; }
                                        break;
                                    case 'ssdtOr':
                                    case 'disaAml':
                                    case 'whiteB':
                                    case 'blackB':
                                    case 'forceKx':
                                    case 'hideVl':
                                        $sorting='';

                                        if ($type === 'ssdtOr') {
                                            $dataPath='ACPI/SortedOrder';
                                            $sorting='<td class="ssdt-sortable"><i class="fa icon-arrows-v"></i></td>';
                                        }
                                        if ($type === 'disaAml') $dataPath='ACPI/DisabledAML';
                                        if ($type === 'whiteB') $dataPath='Boot/WhiteList';
                                        if ($type === 'blackB') $dataPath='Boot/BlackList';
                                        if ($type === 'forceKx') $dataPath='KernelAndKextPatches/ForceKextsToLoad';
                                        if ($type === 'hideVl') $dataPath='GUI/Hide';

                                        foreach ($patches as $p) { ?>
                                            <tr data-path="<?php echo $dataPath; ?>" data-dpath="<?php echo $dataPath; ?>" data-index="<?php echo $i; ?>">
                                                <td class="inline-text" data-field="<?php echo $i; ?>"><?php echo $p; ?></td>
                                                <?php echo $sorting; ?>
                                            </tr>
                                            <?php ++$i; }
                                        break;
                                    case 'disaDrv':
                                        $drivers = array(
                                            '',
                                            '.:: 32 bit ::.',
                                            'Ps2KeyboardDxe','Ps2MouseAbsolutePointerDxe',
                                            '.:: 64 bit ::.',
                                            'NvmExpressDxe',
                                            '.:: 32 & 64 bit ::.',
                                            'Ps2MouseDxe','UsbMouseDxe','VBoxExt2','VBoxExt4','XhciDxe',
                                            '.:: UEFI ::.',
                                            'CsmVideoDxe','DataHubDxe','EmuVariableUefi','OsxAptioFixDrv',
                                            'OsxAptioFix2Drv','OsxLowMemFixDrv','PartitionDxe',
                                            '.:: '.$text['common'].' ::.',
                                            'VBoxHfs'
                                        );

                                        foreach ($patches as $p) { ?>
                                            <tr data-path="placeholder" data-dpath="DisableDrivers" data-index="<?php echo $i; ?>">
                                                <td>
                                                    <select class="cce-sel select-inTable" data-path="DisableDrivers" data-field="<?php echo $i; ?>">
                                                        <?php for ($a=0, $len=count($drivers); $a<$len; $a++) {

                                                            if (substr($drivers[$a],0,2) !== '.:') { ?>
                                                                <option value="<?php echo $drivers[$a]; ?>" <?php echo isSelected($drivers[$a], $p); ?>><?php echo $drivers[$a]; ?></option>
                                                            <?php } else { ?>
                                                                <option value="" class="sel-sep" disabled><?php echo $drivers[$a]; ?></option>
                                                            <?php }
                                                        } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <?php ++$i; }
                                        break;
                                    case 'vbiosP':
                                        foreach ($patches as $p) { ?>
                                            <tr data-path="Graphics/PatchVBiosBytes/<?php echo $i; ?>" data-dpath="Graphics/PatchVBiosBytes" data-index="<?php echo $i; ?>">
                                                <td class="inline-text" data-field="Find"><?php echo getPropVal($p, 'Find'); ?></td>
                                                <td class="inline-text" data-field="Replace"><?php echo getPropVal($p, 'Replace'); ?></td>
                                            </tr>
                                            <?php ++$i; }
                                        break;
                                    case 'addProp':
                                        $device = array('ATI','NVidia','IntelGFX','LAN','WIFI','Firewire','SATA','IDE','HDA','HDMI','LPC','SmBUS','USB');

                                        foreach ($patches as $p) { ?>
                                            <tr data-path="Devices/AddProperties/<?php echo $i; ?>" data-dpath="Devices/AddProperties" data-index="<?php echo $i; ?>">
                                                <td>
                                                    <select class="cce-sel select-inTable" data-path="Devices/AddProperties/<?php echo $i; ?>" data-field="Device">
                                                        <?php foreach ($device as $dev) { ?>
                                                            <option value="<?php echo $dev; ?>" <?php echo isSelected($dev, $p['Device']); ?>><?php echo $dev; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td class="inline-text" data-field="Key"><?php echo getPropVal($p, 'Key'); ?></td>
                                                <td class="inline-text" data-field="Value"><?php echo getPropVal($p, 'Value'); ?></td>
                                                <td class="inline-text" data-field="Comment"><?php echo getPropVal($p, 'Comment'); ?></td>
                                            </tr>
                                            <?php ++$i; }
                                        break;
                                    case 'cPropM':
                                        foreach ($patches as $p) {
                                            $selected = $i == 0 ? 'entry-selected entry-active ':''; ?>
                                            <tr data-path="Devices/Arbitrary/<?php echo $i; ?>" data-dpath="Devices/Arbitrary" class="<?php echo $selected; ?>cpropm" data-index="<?php echo $i; ?>">
                                                <td class="inline-text" data-field="Comment"><?php echo getPropVal($p, 'Comment'); ?></td>
                                                <td class="inline-text" data-field="PciAddr"><?php echo getPropVal($p, 'PciAddr'); ?></td>
                                            </tr>
                                            <?php ++$i; }
                                        break;
                                    case 'cPropS':
                                        foreach ($patches as $p) { ?>
                                            <tr data-path="Devices/Arbitrary/0/CustomProperties/<?php echo $i; ?>" data-dpath="Devices/Arbitrary/0/CustomProperties" data-index="<?php echo $i; ?>">
                                                <td class="inline-text" data-field="Key"><?php echo getPropVal($p, 'Key'); ?></td>
                                                <td class="inline-text" data-field="Value"><?php echo getPropVal($p, 'Value'); ?></td>
                                            </tr>
                                            <?php ++$i; }
                                        break;
                                    case 'subCEn':
                                        foreach ($patches as $p) {
                                            $getTitle = getEntryTitleType($p); ?>
                                            <tr class="subcen-tr" data-path="GUI/Custom/Entries/<?php echo $extra[0]; ?>/SubEntries/<?php echo $i; ?>"
                                                    data-dpath="GUI/Custom/Entries/<?php echo $extra[0]; ?>/SubEntries" data-index="<?php echo $i; ?>">
                                                <td class="inline-text ftitle" data-field="<?php echo $getTitle === '' ? 'Title':$getTitle; ?>">
                                                    <?php echo $getTitle !== '' ? $p[$getTitle]:''; ?>
                                                </td>
                                                <td class="inline-text" data-field="AddArguments"><?php echo getPropVal($p, 'AddArguments'); ?></td>
                                                <td>
                                                    <input class="cce-checkbox centry-ftitle" data-field="FullTitle" type="checkbox" <?php echo getPropVal($p, 'FullTitle') != '' ? 'checked':''; ?> />
                                                </td>
                                                <td>
                                                    <input class="cce-checkbox" data-field="CommonSettings" type="checkbox" <?php echo isChecked($p, 'CommonSettings'); ?> />
                                                </td>
                                            </tr>
                                            <?php ++$i; }
                                        break;
                                    case 'cLegE':
                                        $vTypes = array('Other', 'Windows', 'Linux');

                                        foreach ($patches as $p) {
                                            $getTitle = getEntryTitleType($p); ?>
                                            <tr data-path="GUI/Custom/Legacy/<?php echo $i; ?>" data-dpath="GUI/Custom/Legacy" data-index="<?php echo $i; ?>">
                                                <td class="inline-text" data-field="Volume"><?php echo getPropVal($p, 'Volume'); ?></td>
                                                <td class="inline-text ftitle" data-field="<?php echo $getTitle === '' ? 'Title':$getTitle; ?>">
                                                    <?php echo $getTitle !== '' ? $p[$getTitle]:''; ?>
                                                </td>
                                                <td class="inline-text" data-field="Hotkey"><?php echo getPropVal($p, 'Hotkey'); ?></td>
                                                <td>
                                                    <input class="cce-checkbox centry-ftitle" data-field="FullTitle" type="checkbox" <?php echo getPropVal($p, 'FullTitle') != '' ? 'checked':''; ?> />
                                                </td>
                                                <td>
                                                    <input class="cce-checkbox" data-field="Hidden" type="checkbox" <?php echo isChecked($p, 'Hidden'); ?> />
                                                </td>
                                                <td>
                                                    <input class="cce-checkbox" data-field="Disabled" type="checkbox" <?php echo isChecked($p, 'Disabled'); ?> />
                                                </td>
                                                <td>
                                                    <select class="cce-sel select-inTable" data-path="GUI/Custom/Legacy/<?php echo $i; ?>" data-field="Type">
                                                        <?php foreach ($vTypes as $tp) { ?>
                                                            <option value="<?php echo $tp; ?>" <?php echo isSelected($tp, $p['Type']); ?>><?php echo $tp; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <?php ++$i; }
                                        break;
                                    case 'cToolE':
                                        foreach ($patches as $p) {
                                            $getTitle = getEntryTitleType($p); ?>
                                            <tr data-path="GUI/Custom/Tool/<?php echo $i; ?>" data-dpath="GUI/Custom/Tool" data-index="<?php echo $i; ?>">
                                                <td class="inline-text" data-field="Volume"><?php echo getPropVal($p, 'Volume'); ?></td>
                                                <td class="inline-text" data-field="Path"><?php echo getPropVal($p, 'Path'); ?></td>
                                                <td class="inline-text ftitle" data-field="<?php echo $getTitle === '' ? 'Title':$getTitle; ?>">
                                                    <?php echo $getTitle !== '' ? $p[$getTitle]:''; ?>
                                                </td>
                                                <td class="inline-text" data-field="Arguments"><?php echo getPropVal($p, 'Arguments'); ?></td>
                                                <td class="inline-text" data-field="Hotkey"><?php echo getPropVal($p, 'Hotkey'); ?></td>
                                                <td>
                                                    <input class="cce-checkbox centry-ftitle" data-field="FullTitle" type="checkbox" <?php echo getPropVal($p, 'FullTitle') != '' ? 'checked':''; ?> />
                                                </td>
                                                <td>
                                                    <input class="cce-checkbox" data-field="Hidden" type="checkbox" <?php echo isChecked($p, 'Hidden'); ?> />
                                                </td>
                                                <td>
                                                    <input class="cce-checkbox" data-field="Disabled" type="checkbox" <?php echo isChecked($p, 'Disabled'); ?> />
                                                </td>
                                            </tr>
                                            <?php ++$i; }
                                        break;
                                    case 'mNvI':
                                        foreach ($patches as $p) { ?>
                                            <tr data-path="Graphics/NVIDIA/<?php echo $i; ?>" data-dpath="Graphics/NVIDIA" data-index="<?php echo $i; ?>">
                                                <td class="inline-text" data-field="Model"><?php echo getPropVal($p, 'Model'); ?></td>
                                                <td class="inline-text" data-field="IOPCIPrimaryMatch"><?php echo getPropVal($p, 'IOPCIPrimaryMatch'); ?></td>
                                                <td class="inline-text" data-field="IOPCISubDevId"><?php echo getPropVal($p, 'IOPCISubDevId'); ?></td>
                                                <td class="inline-text" data-field="VRAM"><?php echo getPropVal($p, 'VRAM'); ?></td>
                                                <td class="inline-text" data-field="VideoPorts"><?php echo getPropVal($p, 'VideoPorts'); ?></td>
                                                <td>
                                                    <input class="cce-checkbox" data-field="LoadVBios" type="checkbox" <?php echo isChecked($p, 'LoadVBios'); ?> />
                                                </td>
                                            </tr>
                                            <?php ++$i; }
                                        break;
                                    case 'mAI':
                                        foreach ($patches as $p) { ?>
                                            <tr data-path="Graphics/ATI/<?php echo $i; ?>" data-dpath="Graphics/ATI" data-index="<?php echo $i; ?>">
                                                <td class="inline-text" data-field="Model"><?php echo getPropVal($p, 'Model'); ?></td>
                                                <td class="inline-text" data-field="IOPCIPrimaryMatch"><?php echo getPropVal($p, 'IOPCIPrimaryMatch'); ?></td>
                                                <td class="inline-text" data-field="IOPCISubDevId"><?php echo getPropVal($p, 'IOPCISubDevId'); ?></td>
                                                <td class="inline-text" data-field="VRAM"><?php echo getPropVal($p, 'VRAM'); ?></td>
                                            </tr>
                                            <?php ++$i; }
                                        break;
                                    case 'SMram':
                                        $Rfreq = array(200,266,333,366,400,433,533,667,800,1066,1333,
                                            1600,1800,2000,2133,2200,2400,2600);
                                        $type = array('DDR','DDR2','DDR3','DDR4');

                                        foreach ($patches as $p) { ?>
                                            <tr data-path="SMBIOS/Memory/Modules/<?php echo $i; ?>" data-dpath="SMBIOS/Memory/Modules" data-index="<?php echo $i; ?>">
                                                <td>
                                                    <select class="cce-sel select-inTable" data-path="SMBIOS/Memory/Modules/<?php echo $i; ?>" data-field="Slot">
                                                        <option value=""></option>
                                                        <?php for ($f=0; $f<=23; ++$f) { ?>
                                                            <option value="<?php echo $f; ?>" <?php echo isSelected($f, $p['Slot']); ?>><?php echo $f; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="cce-sel select-inTable" data-path="SMBIOS/Memory/Modules/<?php echo $i; ?>" data-field="Size">
                                                        <option value=""></option>
                                                        <?php for ($ss=1024; $ss<16400;$ss*=2) { ?>
                                                            <option value="<?php echo $ss; ?>" <?php echo isSelected($ss, $p['Size']); ?>><?php echo $ss; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="cce-sel select-inTable" data-path="SMBIOS/Memory/Modules/<?php echo $i; ?>" data-field="Frequency">
                                                        <option value=""></option>
                                                        <?php foreach ($Rfreq as $fq) { ?>
                                                            <option value="<?php echo $fq; ?>" <?php echo isSelected($fq, $p['Frequency']); ?>><?php echo $fq; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="cce-sel select-inTable" data-path="SMBIOS/Memory/Modules/<?php echo $i; ?>" data-field="Type">
                                                        <option value=""></option>
                                                        <?php foreach ($type as $t) { ?>
                                                            <option value="<?php echo $t; ?>" <?php echo isSelected($t, $p['Type']); ?>><?php echo $t; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td class="inline-text" data-field="Vendor"><?php echo getPropVal($p, 'Vendor'); ?></td>
                                                <td class="inline-text" data-field="Serial"><?php echo getPropVal($p, 'Serial'); ?></td>
                                                <td class="inline-text" data-field="Part"><?php echo getPropVal($p, 'Part'); ?></td>
                                            </tr>
                                            <?php ++$i; }
                                        break;
                                    case 'SMslots':
                                        $device = array('ATI','NVidia','IntelGFX','LAN','WIFI','Firewire','HDMI','USB','NVME');
                                        $type = array('PCI' => 0,'PCIe X1' => 1,'PCIe X2' => 2,
                                            'PCIe X4' => 4,'PCIe X8' => 8,'PCIe X16' => 16);

                                        foreach ($patches as $p) { ?>
                                            <tr data-path="SMBIOS/Slots/<?php echo $i; ?>" data-dpath="SMBIOS/Slots" data-index="<?php echo $i; ?>">
                                                <td>
                                                    <select class="cce-sel select-inTable" data-path="SMBIOS/Slots/<?php echo $i; ?>" data-field="Device">
                                                        <option value=""></option>
                                                        <?php foreach ($device as $dev) { ?>
                                                            <option value="<?php echo $dev; ?>" <?php echo isSelected($dev, $p['Device']); ?>><?php echo $dev; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td class="inline-text" data-field="ID"><?php echo getPropVal($p, 'ID'); ?></td>
                                                <td class="inline-text" data-field="Name"><?php echo getPropVal($p, 'Name'); ?></td>
                                                <td>
                                                    <select class="cce-sel select-inTable" data-path="SMBIOS/Slots/<?php echo $i; ?>" data-field="Type">
                                                        <option value=""></option>
                                                        <?php foreach ($type as $k => $v) { ?>
                                                            <option value="<?php echo $v; ?>" <?php echo isSelected($v, $p['Type']); ?>><?php echo $k; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <?php ++$i; }
                                        break;
                                    default:
                                        break;
                                } } ?>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
<?php }