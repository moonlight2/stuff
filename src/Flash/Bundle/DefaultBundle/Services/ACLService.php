<?php

namespace Flash\Bundle\DefaultBundle\Services;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Acl\Dbal\AclProvider;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class ACLService {

    private $context;
    private $provider;

    public function __construct(SecurityContext $context, AclProvider $provider) {
        $this->context = $context;
        $this->provider = $provider;
    }

    public function setOwnerForEntity($entity) {

        $objectIdentity = ObjectIdentity::fromDomainObject($entity);
        $acl = $this->provider->createAcl($objectIdentity);

        $securityIdentity = UserSecurityIdentity::fromAccount(
                        $this->context->getToken()->getUser()
        );

        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        $this->provider->updateAcl($acl);
    }

}

?>
