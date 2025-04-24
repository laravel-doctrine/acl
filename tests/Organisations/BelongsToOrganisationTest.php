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

    public function test_doesnt_have_organisation_when_no_organisations_assigned_single(): void
    {
        $this->assertFalse($this->userSingle->belongsToOrganisation($this->orgMock1));
    }

    public function test_doesnt_have_organisation_when_no_organisations_assigned(): void
    {
        $this->assertFalse($this->user->belongsToOrganisation($this->orgMock1));
    }

    public function test_doesnt_have_role_by_name_when_no_roles_assigned(): void
    {
        $this->assertFalse($this->user->belongsToOrganisation('org1'));
    }

    public function test_doesnt_have_organisation_when_when_other_orgiansation_assigned(): void
    {
        $this->user->setOrganisations([
            entity(Organisation::class)->create(['name' => 'org4'])
        ]);
        $this->assertFalse($this->user->belongsToOrganisation($this->orgMock1));
    }

    public function test_doesnt_have_any_organisations_when_organisation_assigned(): void
    {
        $this->user->setOrganisations([
            $this->orgMock1
        ]);
        $this->assertFalse($this->user->belongsToOrganisation([$this->orgMock2, $this->orgMock3]));
    }

    public function test_doesnt_have_any_organisation_by_name_when_organisation_assigned(): void
    {
        $this->user->setOrganisations([
            $this->orgMock1
        ]);
        $this->assertFalse($this->user->belongsToOrganisation(['org2', 'org3']));
    }

    public function test_doesnt_have_all_organisations_when_organisations_assigned(): void
    {
        $this->user->setOrganisations([
            $this->orgMock1,
            $this->orgMock2
        ]);
        $this->assertFalse($this->user->belongsToOrganisation([$this->orgMock1, $this->orgMock2, $this->orgMock3], true));
    }

    public function test_doesnt_have_all_organisations_by_name_when_organisations_assigned(): void
    {
        $this->user->setOrganisations([
            $this->orgMock1,
            $this->orgMock2
        ]);
        $this->assertFalse($this->user->belongsToOrganisation(['org1', 'org2', 'org3'], true));
    }

    public function test_has_organisation_when_when_organisation_assigned(): void
    {
        $this->user->setOrganisations([
            $this->orgMock1,
        ]);
        $this->assertTrue($this->user->belongsToOrganisation($this->orgMock1));
    }

    public function test_has_organisation_by_name_when_when_organisation_assigned(): void
    {
        $this->user->setOrganisations([
            $this->orgMock1,
        ]);
        $this->assertTrue($this->user->belongsToOrganisation('org1'));
    }

    public function test_has_any_organisation_when_organisation_assigned(): void
    {
        $this->user->setOrganisations([
            $this->orgMock1,
            $this->orgMock2,
            $this->orgMock3
        ]);
        $this->assertTrue($this->user->belongsToOrganisation([$this->orgMock1, $this->orgMock2]));
    }

    public function test_has_all_organisations_when_organisations_assigned(): void
    {
        $this->user->setOrganisations([
            $this->orgMock1,
            $this->orgMock2,
            $this->orgMock3
        ]);
        $this->assertTrue($this->user->belongsToOrganisation([$this->orgMock1, $this->orgMock2, $this->orgMock3], true));
    }

    public function test_has_any_organisation_by_name_when_organisation_assigned(): void
    {
        $this->user->setOrganisations([
            $this->orgMock1,
            $this->orgMock2,
            $this->orgMock3
        ]);
        $this->assertTrue($this->user->belongsToOrganisation(['org1', 'org4']));
    }

    public function test_has_all_organisations_by_name_when_organisations_assigned(): void
    {
        $this->user->setOrganisations([
            $this->orgMock1,
            $this->orgMock2,
            $this->orgMock3
        ]);
        $this->assertTrue($this->user->belongsToOrganisation(['org1', 'org2', 'org3'], true));
    }
}
