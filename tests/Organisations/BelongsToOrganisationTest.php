<?php

use Tests\TestCase;
use Workbench\App\Entities\Organisation;
use Workbench\App\Entities\User;
use Workbench\App\Entities\UserSingleOrg;

class BelongsToOrganisationTest extends TestCase
{
    protected ?User $user;
    protected ?UserSingleOrg $userSingle;

    protected ?Organisation $orgMock1;
    protected ?Organisation $orgMock2;
    protected ?Organisation $orgMock3;

    public function setUp(): void
    {
        parent::setUp();
        $this->user          = entity(User::class)->create();
        $this->userSingle    = entity(UserSingleOrg::class)->create();
        $this->orgMock1      = entity(Organisation::class)->create(['name' => 'org1']);
        $this->orgMock2      = entity(Organisation::class)->create(['name' => 'org2']);
        $this->orgMock3      = entity(Organisation::class)->create(['name' => 'org3']);
    }

    public function test_belongs_to_organisation_various_cases(): void
    {
        // No organisations assigned (single org user)
        $this->assertFalse($this->userSingle->belongsToOrganisation($this->orgMock1));

        // Assign an organisation to userSingle and check positive/negative cases
        $this->userSingle->setOrganisation($this->orgMock1);
        $this->assertTrue($this->userSingle->belongsToOrganisation($this->orgMock1));
        $this->assertTrue($this->userSingle->belongsToOrganisation('org1'));
        $this->assertFalse($this->userSingle->belongsToOrganisation($this->orgMock2));
        $this->assertFalse($this->userSingle->belongsToOrganisation('org2'));

        // No organisations assigned (multi org user)
        $this->assertFalse($this->user->belongsToOrganisation($this->orgMock1));
        $this->assertFalse($this->user->belongsToOrganisation('org1'));

        // Other organisation assigned
        $this->user->setOrganisations([
            entity(Organisation::class)->create(['name' => 'org4'])
        ]);
        $this->assertFalse($this->user->belongsToOrganisation($this->orgMock1));

        // Organisation assigned, check any/all/none by object and name
        $this->user->setOrganisations([$this->orgMock1]);
        $this->assertFalse($this->user->belongsToOrganisation([$this->orgMock2, $this->orgMock3]));
        $this->assertFalse($this->user->belongsToOrganisation(['org2', 'org3']));
        $this->assertTrue($this->user->belongsToOrganisation($this->orgMock1));
        $this->assertTrue($this->user->belongsToOrganisation('org1'));

        // Two organisations assigned
        $this->user->setOrganisations([$this->orgMock1, $this->orgMock2]);
        $this->assertFalse($this->user->belongsToOrganisation([$this->orgMock1, $this->orgMock2, $this->orgMock3], true));
        $this->assertFalse($this->user->belongsToOrganisation(['org1', 'org2', 'org3'], true));

        // Three organisations assigned
        $this->user->setOrganisations([$this->orgMock1, $this->orgMock2, $this->orgMock3]);
        $this->assertTrue($this->user->belongsToOrganisation([$this->orgMock1, $this->orgMock2]));
        $this->assertTrue($this->user->belongsToOrganisation([$this->orgMock1, $this->orgMock2, $this->orgMock3], true));
        $this->assertTrue($this->user->belongsToOrganisation(['org1', 'org4']));
        $this->assertTrue($this->user->belongsToOrganisation(['org1', 'org2', 'org3'], true));
    }
}
