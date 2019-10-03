<?php

class BelongsToOrganisationTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var UserMock3
     */
    protected $user;

    /**
     * @var UserMock4
     */
    protected $userSingle;

    /**
     * @var OrgMock
     */
    protected $orgMock1;
    /**
     * @var OrgMock
     */
    protected $orgMock2;
    /**
     * @var OrgMock
     */
    protected $orgMock3;

    protected function setUp() : void
    {
        $this->user          = new UserMock3;
        $this->userSingle    = new UserMock4;
        $this->orgMock1      = new OrgMock('org1');
        $this->orgMock2      = new OrgMock('org2');
        $this->orgMock3      = new OrgMock('org3');
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
            new OrgMock('org4'),
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

class UserMock3 implements \LaravelDoctrine\ACL\Contracts\BelongsToOrganisations
{
    use \LaravelDoctrine\ACL\Organisations\BelongsToOrganisation;

    protected $organisations = [];

    public function getOrganisations()
    {
        return $this->organisations;
    }

    public function setOrganisations($orgs): void
    {
        $this->organisations = $orgs;
    }
}

class UserMock4 implements \LaravelDoctrine\ACL\Contracts\BelongsToOrganisation
{
    use \LaravelDoctrine\ACL\Organisations\BelongsToOrganisation;

    protected $organisation;

    public function getOrganisation()
    {
        return $this->organisation;
    }

    public function setOrganisation($org)
    {
        $this->organisation = $org;
    }
}

class OrgMock implements \LaravelDoctrine\ACL\Contracts\Organisation
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
